<?php

namespace App\Console\Commands;

use App\Models\Entities\BookingConfirmation;
use App\Models\Entities\Email;
use App\Models\Utils\UtilsAbstract;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\SMS\Facades\SMS;

class ConfirmationBeforeDelivery extends Command
{
    const TYPE_EMAIL = 'EMAIL';
    const TYPE_SMS = 'SMS';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ConfirmationBeforeDelivery:confirmDeliveryDate {--location=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To confirm the delivery date with customer 2 days before the delivery date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            // check begins
            $this->info('Start running confirmationBeforeDelivery at [' . Carbon::now()->format('Y-m-d H:i:s.u') . '].');
            $locations = $this->option('location');
            // get all orders to be delivered after 2 days
            $orders = $this->getOrders($locations);
            $emails = [];
            $bar = $this->output->createProgressBar(count($orders));
            foreach($orders as $order)
            {
                $sentEmails = $this->notify($order);
                foreach($sentEmails as $sentEmail)
                {
                    $emails[] = $sentEmail;
                }
                $bar->advance();
            }
            $bar->finish();
            $this->info(''); // insert new line
            $this->info(count($emails) . ' notifications were queued successfully!');
            // check ends
            $this->info('End running confirmationBeforeDelivery at [' . Carbon::now()->format('Y-m-d H:i:s.u') .'].');
        }
        catch (Exception $ex)
        {
            $this->info($ex->getTraceAsString());
        }
    }
    private function getOrders($locations)
    {
        // 2 days after today which means the delivery date should be >= today and  <= today+2days
        // need to figure out the status in V6 after the order is delivered
        // will the staff updates the status in V6?
        $twoDaysAfterToday = Carbon::today()->addDays(7); // TODO for test
        $query = BookingConfirmation::select(['booking_confirmation.*'])
            ->join('V_V6_QUOTE', 'V_V6_QUOTE.QUOTE_ID', '=',  'booking_confirmation.quote_id')
            ->where(DB::raw('V_V6_QUOTE.UDF1 COLLATE DATABASE_DEFAULT'), '=', DB::raw('booking_confirmation.order_id COLLATE DATABASE_DEFAULT'))
            ->where('booking_confirmation.agreed_date', '=', $twoDaysAfterToday)
            ->where('booking_confirmation.agreed_date', '>=', Carbon::today()) // TODO for test
            ->where('booking_confirmation.active', 1)
            ->with('location')
            //->whereNotIn('V_V6_QUOTE.STATUS_ID', [600, 415]) // TODO for test
            ->orderBy('booking_confirmation.order_id', 'asc');
        if (count($locations) > 0)
        {
            $query->join('locations', 'booking_confirmation.location_id', '=',  'locations.id')
                ->wherein('locations.abbreviation', $locations);
        }
        $orders = $query->get();
        return $orders;
    }
    private function notify($order)
    {
        $customer = $order->order->customer;
        $emails = [];
        if ($customer->MOB_PHONE)
        {
            $emails[] = self::sendSMS($order);
        }
        if ($customer->EMAIL_ADDR)
        {
            $emails[] = self::sendEmail($order);
        }
        if (count($emails) == 0)
        {
            $customerName = $customer->CUST_NAME;
            $orderId = $order->order_id;
            $location = $order->location->name;
            $this->warn("Warning: No confirmation sent! [location: $location], [orderId: $orderId], [customer: $customerName] has no mobile phone and email address!");
        }
        return $emails;
    }
    private static function getViewContent($messageType, $content)
    {
        $body = '';
        //view()->share('content',$content);
        switch($messageType)
        {
            case self::TYPE_EMAIL:
                //$body = view('emails.delivery-confirmation.email')->render();
                $body = View::make('emails.delivery-confirmation.email', ['content' => $content])->render();
                break;
            case self::TYPE_SMS:
                //$body = view('emails.delivery-confirmation.sms')->render();
                $body = View::make('emails.delivery-confirmation.sms', ['content' => $content])->render();
                break;
        }
        return $body;
    }
    private static function sendSMS($order)
    {
        $delivery_date = $order->agreed_date;
        $orderId = $order->order_id;
        $quoteId = $order->quote_id;
        $locationId = $order->location_id;

        $customer = $order->order->customer;

        $toArray[] =  $customer->MOB_PHONE;
        $ccArray = [];
        $subject = "[Two days before delivery] [Order No: $orderId] Delivery Confirmation";

        $content = [
            'orderId' => $orderId,
            'customerName' => $customer->CUST_NAME,
            'deliveryDate' => $delivery_date->format('d/m/Y'),
            'emailId' => '',
            'to' => $toArray,
            'cc' => $ccArray,
            'subject' => $subject,
            'body' => '',
        ];
        $messageType = self::TYPE_SMS;
        $body = self::getViewContent($messageType, $content);
        $templateSMS = 'emails.delivery-confirmation.sms-text';
        $content['body'] = $body;

        $email = New Email();
        $email->quote_id = $quoteId;
        $email->location_id = $locationId;
        $email->order_id = $orderId;
        $email->trans_id = UtilsAbstract::getRandKey();
        $email->type = Email::TYPE_TEXT_DELIVERY_CONFIRMATION;
        $email->from = env('MAIL_FROM_ADDRESS', 'bookingseq@dowell.com.au');
        $email->to = implode(';', $toArray);
        $email->cc = implode(';', $ccArray);
        $email->subject = $subject;
        $email->body = $body;
        $email->status = Email::STATUS_NEW;
        $email->save();
        $content['emailId'] = $email->id;

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
    private static function sendEmail($order)
    {
        $class = 'App\Mail\ConfirmationBeforeDelivery';
        $delivery_date = $order->agreed_date;
        $orderId = $order->order_id;
        $quoteId = $order->quote_id;
        $locationId = $order->location_id;

        $customer = $order->order->customer;
        $toArray[] = $customer->EMAIL_ADDR;
        $ccArray[] = env('MAIL_FROM_ADDRESS', 'bookingseq@dowell.com.au');
        $subject = "[Two days before delivery] [Order No: $orderId] Delivery Confirmation";

        $content = [
            'orderId' => $orderId,
            'customerName' => $customer->CUST_NAME,
            'deliveryDate' => $delivery_date->format('d/m/Y'),
            'emailId' => '',
            'to' => $toArray,
            'cc' => $ccArray,
            'subject' => $subject,
        ];

        $messageType = self::TYPE_EMAIL;
        $body = self::getViewContent($messageType, $content);

        $email = New Email();
        $email->quote_id = $quoteId;
        $email->location_id = $locationId;
        $email->order_id = $orderId;
        $email->trans_id = UtilsAbstract::getRandKey();
        $email->type = Email::TYPE_DELIVERY_CONFIRMATION;
        $email->from = env('MAIL_FROM_ADDRESS', 'bookingseq@dowell.com.au');
        $email->to = implode(';', $toArray);
        $email->cc = implode(';', $ccArray);
        $email->subject = $subject;
        $email->body = $body;
        $email->status = Email::STATUS_NEW;
        $email->save();
        $content['emailId'] = $email->id;

        Mail::send(new $class($content));
        return $email;
    }
}
