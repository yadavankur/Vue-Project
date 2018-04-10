<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entities\Log;
use App\Models\Services\TicketCommentService;
use App\Models\Services\UserService;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class TicketcommentController extends Controller
{
    //
    protected $TicketCommentService; protected $userService; 
    public function __construct( UserService $userService, TicketCommentService $TicketCommentService)
    {   $this->TicketCommentService = $TicketCommentService;   $this->userService = $userService;  }




    public function latestcscomments(Request $request) 
    {   try 
        {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the latest notes
            $notes = $this->TicketCommentService->latestcscomments($request);
            return response()->json($notes);
        } catch (Exception $e) {  return response()->json(['error' => $e->getMessage()], 500);  }
    }

    
}
