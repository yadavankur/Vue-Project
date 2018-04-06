<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entities\Log;
//use App\Models\Services\TicketStatusService;
use App\Models\Services\TicketType1Service;
use App\Models\Services\UserService;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class Tickettype1Controller extends Controller
{
    //
    protected $TicketType1Service; protected $userService; 
    public function __construct( UserService $userService, TicketType1Service $TicketType1Service)
    {   $this->TicketType1Service = $TicketType1Service;   $this->userService = $userService;  }
//------------------------------------------------------------
    public function getTicketType1Table(Request $request)
    //public function displaycs()
    {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
               // $this->validate($request, $rules);         
                $gett1= $this->TicketType1Service->getTicketType1Table($request);
               // $gett1= $this->ticketcsService->displaycs();// 2) get all states
                 return response()->json(['gett1' => $gett1, ]);
                // return response()->json($gett1);
               //  return $gett1;
             } catch (Exception $e) 
             { return response()->json(['error' => $e->getMessage()], 500); }
    }
//-------------------------------------------------------------
public function addTicketType1Table(Request $request)
{   $rules = [ 'price'  =>  'required', 'ticket_no' => 'required'  ];
    try {    $user = JWTAuth::parseToken()->authenticate();
             $this->validate($request, $rules);
             $gett1 = $this->TicketType1Service->addTicketType1Table($request);
           //  $this->TicketTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
           return response()->json(compact('gett1'));
           //  return response()->json(compact('gett1'));
        } catch (Exception $e) 
        {    return $this->handleValidationException($e, $this->TicketType1Service, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
}
//------------------------------------------------------------
//---------------update
public function updateTicketType1(Request $request)
{   $rules = ['id' => 'required', 'price'  =>  'required', ];
try {
    $user = JWTAuth::parseToken()->authenticate();
    $this->validate($request, $rules);
    $gett1 = $this->TicketType1Service->updateTicketType1Table($request);
   // $this->TicketTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
    return response()->json(compact('gett1'));
} catch (Exception $e) {
    return $this->handleValidationException($e, $this->TicketType1Service, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
}
}

//-----------------delete
public function deleteTicketType1(Request $request)
{
    $rules = [ 'id'  =>  'required',   ];
    try {  $user = JWTAuth::parseToken()->authenticate();    $this->validate($request, $rules);
           $gett1 = $this->TicketType1Service->deleteTicketType1Table($request);
          // $this->TicketTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
           return response()->json(compact('gett1'));
        }
    catch (Exception $e)
    { return $this->handleValidationException($e, $this->TicketType1Service, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
    }
}

    //----------------------pagination-------
    public function getByPaginate(Request $request)
    {
        try {
            // 1) first get user from token to check validation
             $user = JWTAuth::parseToken()->authenticate();
            // 2) get all permissions
             $tt= $this->TicketType1Service->getByPaginate($request);
            // $tt= $this->ticketcsService->getlastcsticket($request);
            
            return response()->json($tt);
        } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500);  }
    }

}
