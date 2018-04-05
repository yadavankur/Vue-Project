<?php

namespace App\Console\Commands;

use App\Models\Entities\CPM_Comment;
use App\Models\Entities\CPM_OrderMatrix;
use App\Models\Entities\Email;
use App\Models\Entities\Log;
use App\Models\Entities\Message;
use App\Models\Entities\User;
use App\Models\Utils\UtilsAbstract;
use Carbon\Carbon;
use Exception;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class CheckDueActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CheckDueActivity:notifyUsers {--location=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check whether the activity is due and send notification to users';

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
            $this->info('Start running check due activities at [' . Carbon::now()->format('Y-m-d H:i:s.u') . '].');
            $locations = $this->option('location');
            $this->info('begin setting flag of due activities at [' . Carbon::now()->format('Y-m-d H:i:s.u') . '].');
            // set the due flag for overdue activities
            self::setDueFlag($locations);
            $this->info('end setting flag of due activities at [' . Carbon::now()->format('Y-m-d H:i:s.u') . '].');
            // 1) get all uncompleted sequencing activities
            // 2) check the due day to see whether it's one day ahead of due day
            // 3) if yes, then notify users and their managers via email
            // 4) if it's overdue, then notify users and their managers via email
            $dueActivities = $this->getDueActivities($locations);
            $emails = [];
            $bar = $this->output->createProgressBar(count($dueActivities));
            foreach($dueActivities as $dueActivity)
            {
                $emails[] = $this->notify($dueActivity);
                $bar->advance();
            }
            $bar->finish();
            $this->info(''); // insert new line
            $this->info(count($emails) . ' notifications were queued successfully!');
            // check ends
            $this->info('End running check due activities at [' . Carbon::now()->format('Y-m-d H:i:s.u') .'].');
        }
        catch (Exception $ex)
        {
            $this->info($ex->getTraceAsString());
        }
    }
    private function getDueActivities($locations)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $query = CPM_OrderMatrix::select([
            'cpm_order_matrixes.*'
        ])
            ->join('cpm_activities', 'cpm_activities.id', '=',  'cpm_order_matrixes.activity_id')
            ->where('cpm_order_matrixes.active', 1)
            ->where('cpm_activities.active', 1)
            ->where('cpm_order_matrixes.is_needed', 1)
            ->where('cpm_order_matrixes.status_id', '<>', CPM_OrderMatrix::STATUS_COMPLETED)
            ->with('associatedUserUsers')
            ->with('associatedUserGroups')
            ->with('associatedManagerUsers')
            ->with('associatedManagerGroups')
            ->orderBy('cpm_activities.id', 'asc');
        if (count($locations) > 0)
        {
            $query->join('locations', 'cpm_order_matrixes.location_id', '=',  'locations.id')
                ->wherein('locations.abbreviation', $locations);
        }
        $query->where(function ($query) use($today, $tomorrow) {
            $query->where(function ($query) use($today, $tomorrow) {
                $query->whereNotNull('cpm_order_matrixes.delivery_date');
                $query->where(function ($query) use($today, $tomorrow) {
                    $query->where('cpm_order_matrixes.delivery_date', '<=', $today) // overdue
                    ->orWhere('cpm_order_matrixes.delivery_date', $tomorrow); // due tomorrow
                });
            });
            $query->orWhere(function ($query) use($today, $tomorrow) {
                $query->orWhereNull('cpm_order_matrixes.delivery_date');
                $query->where(function ($query) use($today, $tomorrow) {
                    $query->where('cpm_order_matrixes.end_date', '<=', $today) // overdue
                    ->orWhere('cpm_order_matrixes.end_date', $tomorrow); // due tomorrow
                });
            });
        });

        $dueActivities = $query->get();
        return $dueActivities;
    }

    private function notify($dueActivity)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $class = 'App\Mail\DueTodayEmail';
        $dueType = 'Due Today';
        $delivery_date = $dueActivity->delivery_date ?? $dueActivity->end_date;
        if ($delivery_date < $today)
        {
            // over due
            $class = 'App\Mail\OverdueEmail';
            $dueType = 'Overdue';
        }
        else if ($delivery_date == $today)
        {
            // due today
            $class = 'App\Mail\DueTodayEmail';
            $dueType = 'Due Today';
        }
        else if ($delivery_date == $tomorrow)
        {
            // due tomorrow
            $class = 'App\Mail\DueTomorrowEmail';
            $dueType = 'Due Tomorrow';
        }
        return $this->sendEmail($dueActivity, $class, $dueType);
    }
    private function sendEmail($dueActivity, $class, $dueType)
    {
        $delivery_date = $dueActivity->delivery_date ?? $dueActivity->end_date;
        $dueDay = $delivery_date; //Carbon::createFromFormat('Y-m-d H:i:s.u', $delivery_date);
        $orderId = $dueActivity->order_id;
        $quoteId = $dueActivity->quote_id;
        $locationId = $dueActivity->location_id;

        // for one activity may have multiple users and managers
        // groups as well
        // get all user email address as to
        $users = $dueActivity->associatedUserUsers;
        $toArray = [];
        $toUserIds = [];
        foreach($users as $user)
        {
            $toArray[$user->user->name] = $user->user->email;
            $toUserIds[] = $user->user->id;
        }
        $userGroups = $dueActivity->associatedUserGroups;
        $userGroupIds = [];
        foreach($userGroups as $userGroup)
        {
            $userGroupIds[] = $userGroup->group->id;
        }
        //$groupUsers =
        $groupUsers = User::join('user_group', 'users.id', '=', 'user_group.user_id')
            ->join('groups', 'groups.id', '=', 'user_group.group_id')
            ->where('users.active', 1)
            ->where('groups.active', 1)
            ->where('user_group.active', 1)
            ->where('groups.active', 1)
            ->wherein('user_group.group_id', $userGroupIds)
            ->get(['users.*']);
        $groupUserEmails = $groupUsers->pluck('email', 'name')->all();
        // merge user emails
        $toArray = array_merge($toArray, $groupUserEmails);
        if (count($toArray) == 0)
        {
            $toArray[] = env('MAIL_FROM_ADDRESS', 'bookingseq@dowell.com.au');
        }
        // get all manager email address as cc
        $managers = $dueActivity->associatedManagerUsers;
        $ccArray = [];
        foreach($managers as $manager)
        {
            $ccArray[$manager->user->name] = $manager->user->email;
        }
        $managerGroups = $dueActivity->associatedManagerGroups;
        $managerGroupIds = [];
        foreach($managerGroups as $managerGroup)
        {
            $managerGroupIds[] = $managerGroup->group->id;
        }
        $groupManagers = User::join('user_group', 'users.id', '=', 'user_group.user_id')
            ->join('groups', 'groups.id', '=', 'user_group.group_id')
            ->where('users.active', 1)
            ->where('groups.active', 1)
            ->where('user_group.active', 1)
            ->where('groups.active', 1)
            ->wherein('user_group.group_id', $managerGroupIds)
            ->get(['users.*']);
        $groupManagerEmails = $groupManagers->pluck('email', 'name')->all();
        // merge manager emails
        $ccArray = array_merge($ccArray, $groupManagerEmails);
        // cc to booking as well
        $ccArray[] = env('MAIL_FROM_ADDRESS', 'bookingseq@dowell.com.au');
        $subject = "[Order No: $orderId] Activity $dueType";

        $content = [
            'orderId' => $orderId,
            'activityName' => $dueActivity->activity->name,
            'dueDay' => $dueDay->format('d/m/Y'),
            'emailId' => '',
            'to' => $toArray,
            'cc' => $ccArray,
            'subject' => $subject,
        ];
        $messageType = strtoupper(str_replace(' ', '', $dueType));
        $body = self::getViewContent($messageType, $content);
        //$body = View::make('emails.notification.overdue', ['content' => $content])->render();
        $email = New Email();
        $email->quote_id = $quoteId;
        $email->location_id = $locationId;
        $email->order_id = $orderId;
        $email->trans_id = UtilsAbstract::getRandKey();
        $email->type = Email::TYPE_NOTIFICATION;
        $email->from = env('MAIL_FROM_ADDRESS', 'bookingseq@dowell.com.au');
        $email->to = implode(';', $toArray);
        $email->cc = implode(';', $ccArray);
        $email->subject = $subject;
        //$email->body = $body;
        $email->status = Email::STATUS_NEW;
        $email->save();
        $content['emailId'] = $email->id;

        $currentDateTime = Carbon::now();
        $userId = env('APP_SYSTEM_USER_ID');
        $messages = [];
        foreach($toUserIds as $toUserId)
        {
            $messages[] = array(
                'type' => $messageType,
                'title' => $subject,
                'message' => $body,
                'to_user_id' => $toUserId,
                'created_at' => $currentDateTime,
                'updated_at' => $currentDateTime,
                'created_by' => $userId,
                'updated_by' => $userId
            );
        }
        if (count($toUserIds) != 0)
        {
            Message::insert($messages);
        }
        else
        {
            $warnMessage = "Sales order No: $orderId, activityName: [" .
                $content['activityName'] .  "], hasn't been assigned to anyone.";
            $this->warn($warnMessage);
        }
        Mail::send(new $class($content));
        return $email;
    }
    private static function setDueFlag($locations)
    {
        try {
            DB::beginTransaction();
            $today = Carbon::today();
            $query = CPM_OrderMatrix::select([
                'cpm_order_matrixes.*'
            ])
                ->join('cpm_activities', 'cpm_activities.id', '=',  'cpm_order_matrixes.activity_id')
                ->where('cpm_order_matrixes.active', 1)
                ->where('cpm_activities.active', 1)
                ->where('cpm_order_matrixes.is_needed', 1)
                ->whereIn('cpm_order_matrixes.status_id', [CPM_OrderMatrix::STATUS_NEW, CPM_OrderMatrix::STATUS_ON_TIME]);
            $query->where(function ($query) use($today) {
                $query->where(function ($query) use($today) {
                    $query->whereNotNull('cpm_order_matrixes.delivery_date')
                        ->where('cpm_order_matrixes.delivery_date', '<', $today); // overdue
                });
                $query->orWhere(function ($query) use($today) {
                    $query->orWhereNull('cpm_order_matrixes.delivery_date')->where('cpm_order_matrixes.end_date', '<', $today); // overdue
                });
            });

            if (count($locations) > 0)
            {
                $query->join('locations', 'cpm_order_matrixes.location_id', '=',  'locations.id')
                    ->wherein('locations.abbreviation', $locations);
            }

            $cpmComments = self::prepareCPMComments($query);
            $count = $query->update(['cpm_order_matrixes.status_id' => CPM_OrderMatrix::STATUS_DELAY]);
            Log::LogEntity(null, "set delay flag to all $count overdue activities!",  __CLASS__ . '::' .__FUNCTION__);

            CPM_Comment::insert($cpmComments);
            DB::commit();
        }
        catch (Exception $ex)
        {
            DB::rollback();
            throw $ex;
        }
    }
    private static function prepareCPMComments($query)
    {
        $dueActivities = $query->get();
        $cpmComments = [];
        $userId = env('APP_SYSTEM_USER_ID');
        $type = CPM_Comment::TYPE_DOWELL;
        $comment = 'Due flag set by system automatically due to overdue!';
        $currentDateTime = Carbon::now();
        foreach($dueActivities as $dueActivity)
        {
            $cpmComments[] = array(
                'quote_id' => $dueActivity->quote_id,
                'location_id' => $dueActivity->location_id,
                'order_id' => $dueActivity->order_id,
                'activity_id' => $dueActivity->activity_id,
                'cpm_order_id' => $dueActivity->id,
                'type' => $type,
                'comment' => $comment,
                'created_at' => $currentDateTime,
                'updated_at' => $currentDateTime,
                'created_by' => $userId,
                'updated_by' => $userId
            );
        }
        return $cpmComments;
    }
    private static function getViewContent($messageType, $content)
    {
        $body = '';
        switch($messageType)
        {
            case Message::TYPE_OVERDUE:

                $body = View::make('emails.message.overdue', ['content' => $content])->render();
                break;
            case Message::TYPE_DUETODAY:
                $body = View::make('emails.message.duetoday', ['content' => $content])->render();
                break;
            case Message::TYPE_DUETOMORROW:
                $body = View::make('emails.message.duetomorrow', ['content' => $content])->render();
                break;
        }
        return $body;
    }
}
