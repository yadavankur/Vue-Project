<?php

namespace App\Providers;

use App\Models\Services\EmailService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            '1' => 'App\Models\Entities\Location',
            '2' => 'App\Models\Entities\Menu',
            '3' => 'App\Models\Entities\Tab',
            '4' => 'App\Models\Entities\Page',
            '5' => 'App\Models\Entities\Component',
            '6' => 'App\Models\Entities\Process',
        ]);

        Queue::failing(function (JobFailed $event) {
            // $event->connectionName
            // $event->job
            // $event->exception
            Log::info('AppServiceProvider - boot- Queue::failing');
            // get the displayName
            $displayName = $event->job->payload()['displayName'];
            if ($displayName == 'sms')
            {
                $data = $event->job->payload()['data']['data'];
                $emailId = $data['emailId'];
            }
            else
            {
                // get the object from payload
                $jobCommand = unserialize($event->job->payload()['data']['command']);
                $queueMailable = $jobCommand->mailable;
                $emailId = $queueMailable->content['emailId'];
            }
            EmailService::updateEmailStatusFailure($emailId);
        });
        Queue::before(function (JobProcessing $event) {
            // $event->connectionName
            // $event->job
            // $event->job->payload()
            Log::info('AppServiceProvider - boot- Queue::before');
        });

        Queue::after(function (JobProcessed $event) {
            // $event->connectionName
            // $event->job
            // $event->job->payload()
            Log::info('AppServiceProvider - boot- Queue::after');
            // after inserting the job to the queue
            // insert the email info to the email table as well
            // get the object from payload
            // get the displayName
            $displayName = $event->job->payload()['displayName'];
            if ($displayName == 'sms')
            {
                $data = $event->job->payload()['data']['data'];
                $emailId = $data['emailId'];
            }
            else
            {
                // get the object from payload
                $jobCommand = unserialize($event->job->payload()['data']['command']);
                $queueMailable = $jobCommand->mailable;
                $emailId = $queueMailable->content['emailId'];
            }
            EmailService::updateEmailStatusSuccess($emailId);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
