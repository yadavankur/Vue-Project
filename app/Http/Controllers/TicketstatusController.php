<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entities\Log;
use App\Models\Services\TicketStatusService;
use App\Models\Services\UserService;

use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class TicketstatusController extends Controller
{
    //
    protected $TicketStatusService; protected $userService; 
    public function __construct( UserService $userService, TicketStatusService $TicketStatusService)
    {   $this->TicketStatusService = $TicketStatusService;   
        $this->userService = $userService; 
       
    }

    public function getTicketStatusTable(Request $request)
    {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
                 $gett1= $this->TicketStatusService->getTicketStatusTable();// 2) get all states
                 //return response()->json(['gett1' => $gett1, ]);
                 return $gett1;
             } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
    }
//-------------------------------------------------------------
    public function addTicketStatusTable(Request $request)
    {   $rules = [ 'STATUS'  =>  'required',  ];
        try {    $user = JWTAuth::parseToken()->authenticate();
                 $this->validate($request, $rules);
                 $gett1 = $this->TicketStatusService->addTicketStatusTable($request);
                 $this->TicketStatusService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
                 return response()->json(compact('gett1'));
            } catch (Exception $e) 
            {    return $this->handleValidationException($e, $this->TicketStatusService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
            }
    }
//------------------------------------------------------------
//---------------update
public function updateTicketStatusTable(Request $request)
{   $rules = ['id' => 'required', 'STATUS'  =>  'required', ];
    try {
        $user = JWTAuth::parseToken()->authenticate();
        $this->validate($request, $rules);
        $gett1 = $this->TicketStatusService->updateTicketStatusTable($request);
        $this->TicketStatusService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
        return response()->json(compact('gett1'));
    } catch (Exception $e) {
        return $this->handleValidationException($e, $this->TicketStatusService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
    }
}

    //-----------------delete
    public function deleteTicketStatusTable(Request $request)
    {
        $rules = [ 'id'  =>  'required',   ];
        try {  $user = JWTAuth::parseToken()->authenticate();    $this->validate($request, $rules);
               $gett1 = $this->TicketStatusService->deleteTicketStatusTable($request);
               $this->TicketStatusService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
               return response()->json(compact('gett1'));
            }
        catch (Exception $e)
        { return $this->handleValidationException($e, $this->TicketStatusService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }

    
}
