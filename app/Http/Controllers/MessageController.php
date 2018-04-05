<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\MessageService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class MessageController extends Controller
{
    protected $messageService;
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function getAllMessages(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the associated jobs
            $messages= $this->messageService->getByPaginate($request);
            return response()->json($messages);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateNotificationStatus(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the associated jobs
            $message = $this->messageService->updateNotificationStatus($request);
            $this->messageService->LogEntity($message, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($message);
        } catch (Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500);
            return $this->handleValidationException($e, $this->messageService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getBadgeCount(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the associated jobs
            $badgeCount = $this->messageService->getBadgeCount($request);
            return response()->json($badgeCount);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
