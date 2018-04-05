<?php

namespace App\Models\Services;

use App\Models\Entities\Email;
use App\Models\Entities\Log;
use App\Models\Repositories\EmailRepository;
use Illuminate\Http\Request;

class EmailService extends BaseService
{
    protected $emailRepository;

    public function __construct(EmailRepository $emailRepository)
    {
        $this->emailRepository = $emailRepository;
    }
    public function sendEmail(Request $request) {
        return $this->emailRepository->sendEmail($request);
    }
    public function sendTextMessage(Request $request) {
        return $this->emailRepository->sendTextMessage($request);
    }
    public static function updateEmailStatusSuccess($emailId)
    {
        $email = Email::findOrFail($emailId);
        $email->status = Email::STATUS_SENT;
        $email->save();
        Log::LogEntity($email, 'success', __CLASS__ . '::' .__FUNCTION__);
    }
    public static function updateEmailStatusFailure($emailId)
    {
        $email = Email::findOrFail($emailId);
        $email->status = Email::STATUS_FAILED;
        $email->save();
        Log::LogEntity($email, 'success', __CLASS__ . '::' .__FUNCTION__);
    }
    public function getByPaginate(Request $request)
    {
        return $this->emailRepository->getByPaginate($request);
    }
    public function viewAttachment(Request $request)
    {
        return $this->emailRepository->viewAttachment($request);
    }
}