<?php

namespace App\Models\Repositories;

use App\Models\Entities\Asset;
use App\Models\Entities\AssociatedJob;
use App\Models\Entities\BookingConfirmation;
use App\Models\Entities\Email;
use App\Models\Entities\Location;
use App\Models\Entities\V6Quote;
use App\Models\Utils\UtilsAbstract;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\SMS\Facades\SMS;

class EmailRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\Email';
    }

    public function sendTextMessage(Request $request) {

        $authUser = Auth::user();

        // 1) get data from request
        $to = $request->emailTo;
        $toReplaced = str_replace(';', ',', $to);
        $toArray = explode(',', $toReplaced);

        $cc = $request->emailCc;
        $ccReplaced = str_replace(';', ',', $cc);
        $ccArray = explode(',', $ccReplaced);

        $subject = $request->emailSubject;
        $body = $request->emailContent;
        $templateType = $request->templateType; // for text message not used

        $order = $request->order;
        $orderId = $order['UDF1'];
        $type = $request->type;
        $quoteId = $order['QUOTE_ID'];
        $locationAbbr = $order['QUOTE_NUM_PREF'];
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;

        $replyTo = null;
        $replyToName = null;

        // 2) judge the type to send proper template email
        $class = '';
        $templateSMS = 'emails.order.single-confirmation-text';
        switch ($type)
        {
            case Email::TYPE_TEXT_MESSAGE:
                $class = 'App\Mail\SingleConfirmationText';
                break;
            case Email::TYPE_TEXT_MESSAGE_MONEY_OWING:
                $class = 'App\Mail\MoneyOwingText';
                break;
            default:
                // if not set then seen as internal
                $type = Email::TYPE_TEXT_MESSAGE;
                $class = 'App\Mail\SingleConfirmationText';
                break;
        }
        // 2) insert email info to the email table and get id
        $email = New Email();
        $email->quote_id = $quoteId;
        $email->location_id = $locationId;
        $email->order_id = $orderId;
        $email->trans_id = UtilsAbstract::getRandKey();
        $email->type = $type;
        $email->from = $authUser->email;
        $email->to = $to;
        $email->cc = $cc;
        $email->subject = $subject;
        $email->body = $body;
        $email->status = Email::STATUS_NEW;
        $email->save();
        self::AddLog($quoteId, $locationId, $orderId, 'Sent Text Message', 'Confirmation',
            'Sent text message to '. $to);

        // 3) queue emails
        $content = [
            'emailId' => $email->id,
            'to' => $toArray,
            'cc' => $ccArray,
            'replyTo' => $replyTo,
            'replyToName' => $replyToName,
            'title' => $subject,
            'body' => $body,
        ];
        SMS::queue($templateSMS, $content, function($sms) use ($toArray) {
            foreach ($toArray as $to )
            {
                // get rid of all space in the phone number
                $to = preg_replace('/\s+/','',$to);
                $sms->to($to, Email::TEXT_MESSAGE_CARRIER);
            }
        });
        return $email;
    }

    public function sendEmail(Request $request) 
    {
        $authUser = Auth::user();
        //$authUser = $request->user();
        //$authUser = JWTAuth::parseToken()->authenticate();

        // 1) get data from request
        $to = $request->emailTo;  $toReplaced = str_replace(';', ',', $to);   $toArray = explode(',', $toReplaced);
        $cc = $request->emailCc;
        // cc to booking as well
        $cc = $cc . ';' . env('MAIL_FROM_ADDRESS', 'bookingseq@dowell.com.au');
        $cc = $cc . ';' . $authUser->email;

        $ccReplaced = str_replace(';', ',', $cc);
        $ccArray = explode(',', $ccReplaced);

        $subject = $request->emailSubject;
        $body = $request->emailContent;
        $templateType = $request->templateType;

        $order = $request->order;
        $orderId = $order['UDF1'];
        $quoteId = $order['QUOTE_ID'];
        $locationAbbr = $order['QUOTE_NUM_PREF'];
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;

        $type = $request->type;

        $replyTo = null;
        $replyToName = null;

        // attachments
        $attachments = $request->attachments;
        $assetIds = [];
        foreach($attachments as $attachment)
        {
            // create asset
            $realPath = UtilsAbstract::getRealPath($attachment['fullPath']);
            $asset = Asset::registerAsset($attachment['fileName'], file_get_contents($realPath));
            $assetIds[] = $asset->asset_id;
        }
        // 2) judge the type to send proper template email
        $class = '';
        switch ($type)
        {
            case Email::TYPE_EXTERNAL:
                switch ($templateType)
                {
                    case Email::TEMPLATE_ORDER_CONFIRMATION:
                        $class = 'App\Mail\OrderConfirmation';
                        break;
                    case Email::TEMPLATE_SINGLE_EMAIL_CONFIRMATION:
                        $class = 'App\Mail\SingleConfirmationEmail';
                        break;
                    case Email::TEMPLATE_LIST_EMAIL_CONFIRMATION:
                        $class = 'App\Mail\ListConfirmationEmail';
                        // create attached pdf
                        $associatedJobIds = $request->selectedJobIds;
                        $asset = self::createAttachedPDF($quoteId, $locationId, $orderId, $associatedJobIds);
                        $assetIds[] = $asset->asset_id;
                        break;
                    default:
                        // error
                        break;
                }
                break;
            case Email::TYPE_EXPEDITE_ENQUIRY:
                $class = 'App\Mail\ExpediteEnquiry';
                break;
            case Email::TYPE_EXPEDITE_FEEDBACK:
                $class = 'App\Mail\ExpediteEnquiry';
                break;
            case Email::TYPE_TEXT_MESSAGE:
                $class = 'App\Mail\SingleConfirmationText';
                // add @messagenet.com.au to each phone number
                //$toArray = array_map(function($value) { return $value . Email::TEXT_MESSAGE_GATEWAY; }, $toArray);
                array_walk($toArray, function(&$value) { $value .= Email::TEXT_MESSAGE_GATEWAY; });
                break;
            case Email::TYPE_INTERNAL:
            default:
                // if not set then seen as internal
                $type = Email::TYPE_INTERNAL;
                $class = 'App\Mail\ExpediteEnquiry';
                break;
        }
        // 2) insert email info to the email table and get id
        $email = New Email();
        $email->quote_id = $quoteId;
        $email->location_id = $locationId;
        $email->order_id = $orderId;
        $email->trans_id = UtilsAbstract::getRandKey();
        $email->type = $type;
        $email->from = $authUser->email;
        $email->to = $to;
        $email->cc = $cc;
        $email->subject = $subject;
        $email->body = $body;
        $email->status = Email::STATUS_NEW;
        $email->attached_asset_ids = implode(',', $assetIds);
        $email->save();
        self::AddLog($quoteId, $locationId, $orderId, 'Sent Email : ' .  $type , 'Confirmation',
            'Sent email to '. $to . '');
        // 3) queue emails
        $content = [
            'emailId' => $email->id,
            'to' => $toArray,
            'cc' => $ccArray,
            'attachedAssetIds' => $assetIds,
            'replyTo' => $replyTo,
            'replyToName' => $replyToName,
            'title' => $subject,
            'body' => $body,
        ];
        Mail::send(new $class($content, $order));
        return $email;

    }

    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;

        $search = $request->filter;
        $type = $request->type;

        $query = $this->model->select(['emails.*'])
            ->join('users', 'users.id', '=', 'emails.created_by')
            ->where('emails.order_id', $orderId)
            ->where('emails.quote_id', $quoteId)
            ->where('emails.location_id', $locationId)
            ->where('emails.active', 1)
            ->where('emails.type', $type)
            ->orderBy($sortBy, $sortDirection);

        if ($search) {
            $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) {
                $query->where('emails.status', 'LIKE', $like)
                    ->orWhere('emails.subject','LIKE', $like)
                    ->orWhere('users.name','LIKE', $like)
                    ->orWhere('emails.from','LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }

    public static function createAttachedPDF($quoteId, $locationId, $orderId, $associatedJobIds)
    {
        // how to print the jobs? There are two possibilities.
        // 1) for one selected job, it has lots of associated jobs
        // thus print info of all those associated jobs
        // 2) Simply print info of all selected jobs
        // here need to tweak and confirm with MG, DL and DD.

        // get customer info of the main order
        $bookingOrder = BookingConfirmation::with('location')
            ->where('quote_id', $quoteId)
            ->where('location_id', $locationId)
            ->where('order_id', $orderId)->where('active', 1)
            ->first();
        if (!$bookingOrder instanceof BookingConfirmation)
        {
            throw new Exception('The order is invalid!');
        }

        // get all selected jobs
        $associatedJobs = AssociatedJob::wherein('id', $associatedJobIds)
            ->where('active', 1)->get();
        // ------------------------------
        // need to reconsider because needs information from service db
        // get details of jobs in quote table
        $quoteIds = $associatedJobs->pluck('quote_id')->all();
        $orderJobs = V6Quote::wherein('QUOTE_ID', $quoteIds)->get();
        $orderIds = $orderJobs->pluck('UDF1')->all();
        // get booking info of all selected jobs
        $selectedBookingOrders = BookingConfirmation::wherein('order_id', $orderIds)
            ->where('active', 1)->with('order')->get();
        $dateRangeFrom = $selectedBookingOrders->max('agreed_date');
        $dateRangeTo = $selectedBookingOrders->min('agreed_date');
        // ------------------------------
        $content = array(
            'dateRangeFrom' => $dateRangeFrom,
            'dateRangeTo' => $dateRangeTo,
            'repName' => $bookingOrder->order->sales_person? $bookingOrder->order->sales_person->USER_NAME : '',
            'customerName' => $bookingOrder->order->customer? $bookingOrder->order->customer->CUST_NAME : '',
            'supervisorName' => $bookingOrder->order->contact? $bookingOrder->order->contact->CONTACT_NAME : '',
            'supervisorMobile' => $bookingOrder->order->contact? ($bookingOrder->order->contact->MOBILE ?? $bookingOrder->order->contact->PHONE ?? ''): '',
            'supervisorEmail' => $bookingOrder->order->contact? $bookingOrder->order->contact->EMAIL_ADDR : '',
        );
        $header = View::make('pdf.confirmation-email-list-header', ['content' => $content])->render();

        // calculate the pages
        $rowsPerPage = 12;
        $pages = ceil($orderJobs->count() / $rowsPerPage);

        $pdf = SnappyPdf::loadView('pdf.confirmation-email-list', ['items' => $selectedBookingOrders, 'pages' => range(1, $pages), 'rowsPerPage' => $rowsPerPage])
            ->setOption('header-html', $header);
        // file name [orderId + ]
        $fileName = $bookingOrder->location->abbreviation . '_' . $quoteId . '_' . $orderId . '_' . Carbon::now()->format('YmdHis') . '.pdf';
        $asset = Asset::registerAsset($fileName, $pdf->output());
        return $asset;
    }
    public static function viewAttachedPDF($quoteId, $locationId, $orderId, $associatedJobIds, $view)
    {
        // how to print the jobs? There are two possibilities.
        // 1) for one selected job, it has lots of associated jobs
        // thus print info of all those associated jobs
        // 2) Simply print info of all selected jobs
        // here need to tweak and confirm with MG, DL and DD.

        // get customer info of the main order
        $bookingOrder = BookingConfirmation::with('location')
            ->where('quote_id', $quoteId)
            ->where('location_id', $locationId)
            ->where('order_id', $orderId)->where('active', 1)
            ->first();
        if (!$bookingOrder instanceof BookingConfirmation)
        {
            throw new Exception('The order is invalid!');
        }

        // get all selected jobs
        $associatedJobs = AssociatedJob::wherein('id', $associatedJobIds)
            ->where('active', 1)->get();
        // ------------------------------
        // need to reconsider because needs information from service db
        // get details of jobs in quote table
        $quoteIds = $associatedJobs->pluck('quote_id')->all();
        $orderJobs = V6Quote::wherein('QUOTE_ID', $quoteIds)->get();
        $orderIds = $orderJobs->pluck('UDF1')->all();
        // get booking info of all selected jobs
        $selectedBookingOrders = BookingConfirmation::wherein('order_id', $orderIds)
            ->where('active', 1)->with('order')->get();
        $dateRangeFrom = $selectedBookingOrders->max('agreed_date');
        $dateRangeTo = $selectedBookingOrders->min('agreed_date');
        // ------------------------------
        $content = array(
            'dateRangeFrom' => $dateRangeFrom,
            'dateRangeTo' => $dateRangeTo,
            'repName' => $bookingOrder->order->sales_person? $bookingOrder->order->sales_person->USER_NAME : '',
            'customerName' => $bookingOrder->order->customer? $bookingOrder->order->customer->CUST_NAME : '',
            'supervisorName' => $bookingOrder->order->contact? $bookingOrder->order->contact->CONTACT_NAME : '',
            'supervisorMobile' => $bookingOrder->order->contact? ($bookingOrder->order->contact->MOBILE ?? $bookingOrder->order->contact->PHONE ?? ''): '',
            'supervisorEmail' => $bookingOrder->order->contact? $bookingOrder->order->contact->EMAIL_ADDR : '',
        );
        $header = View::make('pdf.confirmation-email-list-header', ['content' => $content])->render();

        // calculate the pages
        $rowsPerPage = 12;
        $pages = ceil($orderJobs->count() / $rowsPerPage);
        // 'pdf.confirmation-email-list'
        $pdf = SnappyPdf::loadView($view, ['items' => $selectedBookingOrders, 'pages' => range(1, $pages), 'rowsPerPage' => $rowsPerPage])
            ->setOption('header-html', $header);
        $fileName = $bookingOrder->location->abbreviation . '_' . $quoteId . '_' . $orderId . '_' . Carbon::now()->format('YmdHis') . '.pdf';
        return $pdf->inline($fileName);
    }
}