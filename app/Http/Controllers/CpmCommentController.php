<?php

namespace App\Http\Controllers;

use App\Models\Entities\CPM_Comment;
use App\Models\Entities\Log;
use App\Models\Services\CpmCommentService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CpmCommentController extends Controller
{
    protected $cpmCommentService;
    public function __construct(CpmCommentService $cpmCommentService)
    {
        $this->cpmCommentService = $cpmCommentService;
    }
    public function saveDowellNotes(Request $request) {
        $rules = [
            'quoteId' =>  'required',
            'locationId' =>  'required',
            'orderId'  =>  'required',
            'activityId'  =>  'required',
            'deliveryDate' => 'required',
            'notes'  =>  'required',
            'status' => 'required',
        ];
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            // 2) save notes
            $comment = $this->cpmCommentService->saveNotes($request, CPM_Comment::TYPE_DOWELL);
            $this->cpmCommentService->LogEntity($comment, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($comment);
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->cpmCommentService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getActivityNotesHistoryList(Request $request) {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all
            $notes= $this->cpmCommentService->getByPaginate($request, CPM_Comment::TYPE_DOWELL);
            return response()->json($notes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getLatestNotes(Request $request) {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the latest notes
            $notes = $this->cpmCommentService->getLatestNotes($request);
            return response()->json($notes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
