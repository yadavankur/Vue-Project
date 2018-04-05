<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entities\Log;
//use App\Models\Services\TicketStatusService;
use App\Models\Services\TicketTypeService;
use App\Models\Services\UserService;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class TickettypeController extends Controller
{
    //

    protected $TicketTypeService; protected $userService; 
    public function __construct( UserService $userService, TicketTypeService $TicketTypeService)
    {   $this->TicketTypeService = $TicketTypeService;   $this->userService = $userService;  }

   // protected $TicketStatusService; protected $userService; 
   // public function __construct( UserService $userService, TicketStatusService $TicketStatusService)
   // {   $this->TicketStatusService = $TicketStatusService;   $this->userService = $userService;  }

    public function getTicketTypeTable(Request $request)
    {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
                 $gett1= $this->TicketTypeService->getTicketTypeTable();// 2) get all states

                return $gett1;
                 //return response()->json(['gett1' => $gett1, ]);
             } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
    }
//-------------------------------------------------------------
public function addTicketTypeTable(Request $request)
{   $rules = [ 'ticket_type'  =>  'required',  ];
    try {    $user = JWTAuth::parseToken()->authenticate();
             $this->validate($request, $rules);
             $gett1 = $this->TicketTypeService->addTicketTypeTable($request);
           //  $this->TicketTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
             return response()->json(compact('gett1'));
        } catch (Exception $e) 
        {    return $this->handleValidationException($e, $this->TicketTypeService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
}
//------------------------------------------------------------
//---------------update
public function updateTicketTypeTable(Request $request)
{   $rules = ['id' => 'required', 'ticket_type'  =>  'required', ];
try {
    $user = JWTAuth::parseToken()->authenticate();
    $this->validate($request, $rules);
    $gett1 = $this->TicketTypeService->updateTicketTypeTable($request);
   // $this->TicketTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
    return response()->json(compact('gett1'));
} catch (Exception $e) {
    return $this->handleValidationException($e, $this->TicketTypeService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
}
}

//-----------------delete
public function deleteTicketTypeTable(Request $request)
{
    $rules = [ 'id'  =>  'required',   ];
    try {  $user = JWTAuth::parseToken()->authenticate();    $this->validate($request, $rules);
           $gett1 = $this->TicketTypeService->deleteTicketTypeTable($request);
          // $this->TicketTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
           return response()->json(compact('gett1'));
        }
    catch (Exception $e)
    { return $this->handleValidationException($e, $this->TicketTypeService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
    }
}



}

