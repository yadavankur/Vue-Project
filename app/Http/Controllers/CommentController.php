<?php

namespace App\Http\Controllers;

use App\Models\Entities\Comment;
use App\Models\Entities\Log;
use App\Models\Services\CommentService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentController extends Controller
{
    protected $commentService;
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }
    public function saveCustomerNotes(Request $request) {
        $rules = [
            'quoteId'  =>  'required',
            'location'  =>  'required',
            'orderId'  =>  'required',
            'notes'  =>  'required',
        ];
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            // 2) save notes
            $comment = $this->commentService->saveNotes($request, Comment::TYPE_CUSTOMER);
            $this->commentService->LogEntity($comment, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($comment);
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->commentService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function saveDowellNotes(Request $request) {
        $rules = [
            'quoteId'  =>  'required',
            'location'  =>  'required',
            'orderId'  =>  'required',
            'noteTypeId' => 'required',
            'notes'  =>  'required',
        ];
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            // 2) save notes
            $comment = $this->commentService->saveNotes($request, Comment::TYPE_DOWELL);
            $this->commentService->LogEntity($comment, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($comment);
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->commentService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getCustomerNotesHistoryList(Request $request) {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all
            $notes= $this->commentService->getByPaginate($request, Comment::TYPE_CUSTOMER);
            return response()->json($notes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getDowellNotesHistoryList(Request $request) {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all
            $notes= $this->commentService->getByPaginate($request, Comment::TYPE_DOWELL);
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
            $notes = $this->commentService->getLatestNotes($request);
            return response()->json($notes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
