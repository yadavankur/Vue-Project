<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entities\Log;
use App\Models\Services\TicketCnStatusService;
use App\Models\Services\UserService;
use App\Models\Services\Testb1Service;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class TicketcnstatusController extends Controller
{
    //
    protected $TicketCnStatusService; protected $userService; 
    public function __construct( UserService $userService, TicketCnStatusService $TicketCnStatusService,Testb1Service $testb1Service)
    {   $this->TicketCnStatusService = $TicketCnStatusService;   
        $this->userService = $userService; 
        $this->testb1Service = $testb1Service;  
    }

    public function getTicketCnStatusTable(Request $request)
    {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
                 $gett1= $this->TicketCnStatusService->getTicketCnStatusTable();// 2) get all states
                 //return response()->json(['gett1' => $gett1, ]);
                 return $gett1;
             } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
    }
//-------------------------------------------------------------
    public function addTicketCnStatusTable(Request $request)
    {   $rules = [ 'STATUS'  =>  'required',  ];
        try {    $user = JWTAuth::parseToken()->authenticate();
                 $this->validate($request, $rules);
                 $gett1 = $this->TicketCnStatusService->addTicketCnStatusTable($request);
                 $this->TicketCnStatusService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
                 return response()->json(compact('gett1'));
            } catch (Exception $e) 
            {    return $this->handleValidationException($e, $this->TicketCnStatusService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
            }
    }
//------------------------------------------------------------
//---------------update
public function updateTicketCnStatusTable(Request $request)
{   $rules = ['id' => 'required', 'STATUS'  =>  'required', ];
    try {
        $user = JWTAuth::parseToken()->authenticate();
        $this->validate($request, $rules);
        $gett1 = $this->TicketCnStatusService->updateTicketCnStatusTable($request);
        $this->TicketCnStatusService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
        return response()->json(compact('gett1'));
    } catch (Exception $e) {
        return $this->handleValidationException($e, $this->TicketCnStatusService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
    }
}

    //-----------------delete
    public function deleteTicketCnStatusTable(Request $request)
    {
        $rules = [ 'id'  =>  'required',   ];
        try {  $user = JWTAuth::parseToken()->authenticate();    $this->validate($request, $rules);
               $gett1 = $this->TicketCnStatusService->deleteTicketCnStatusTable($request);
               $this->TicketCnStatusService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
               return response()->json(compact('gett1'));
            }
        catch (Exception $e)
        { return $this->handleValidationException($e, $this->TicketCnStatusService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }

    
}
