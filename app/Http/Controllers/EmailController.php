<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\EmailService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmailController extends Controller
{
    protected $userService;
    protected $emailService;

    public function __construct(EmailService $emailService,
                                UserService $userService)
    {
        $this->emailService = $emailService;
        $this->userService = $userService;
    }
    public function sendEmail(Request $request) 
    {

        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();

            // 2) get the data
            $email = $this->emailService->sendEmail($request);
            $this->emailService->LogEntity($email, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($email);
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->emailService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }

    public function getEmailHistoryList(Request $request) {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all deviceTypes
            $emails= $this->emailService->getByPaginate($request);
            return response()->json($emails);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sendTextMessage(Request $request) {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();

            // 2) get the data
            $email = $this->emailService->sendTextMessage($request);
            $this->emailService->LogEntity($email, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($email);

        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->emailService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }

    public function viewAttachment(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the associated jobs
            $pdfConfirmationList = $this->emailService->viewAttachment($request);
            return $pdfConfirmationList;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
