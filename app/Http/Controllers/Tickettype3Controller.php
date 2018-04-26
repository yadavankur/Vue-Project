<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entities\Log;
//use App\Models\Services\TicketStatusService;
use App\Models\Services\TicketType3Service;
use App\Models\Services\UserService;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class Tickettype3Controller extends Controller
{
    //
    protected $TicketType3Service; protected $userService; 
    public function __construct( UserService $userService, TicketType3Service $TicketType3Service)
    {   $this->TicketType3Service = $TicketType3Service;   $this->userService = $userService;  }
//------------------------------------------------------------
    public function getTicketType3Table(Request $request)
    //public function displaycs()
    {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
               // $this->validate($request, $rules);         
                $gett1= $this->TicketType3Service->getTicketType3Table($request);
               // $gett1= $this->ticketcsService->displaycs();// 2) get all states
                 return response()->json(['gett1' => $gett1, ]);
                // return response()->json($gett1);
               //  return $gett1;
             } catch (Exception $e) 
             { return response()->json(['error' => $e->getMessage()], 500); }
    }
//-------------------------------------------------------------
public function addTicketType3Table(Request $request)
{   $rules = [// 'price'  =>  'required', 
    'ticket_no' => 'required'  ];
    try {    $user = JWTAuth::parseToken()->authenticate();
             $this->validate($request, $rules);
             $gett1 = $this->TicketType3Service->addTicketType3Table($request);
           //  $this->TicketTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
           return response()->json(compact('gett1'));
           //  return response()->json(compact('gett1'));
        } catch (Exception $e) 
        {    return $this->handleValidationException($e, $this->TicketType3Service, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
}
//------------------------------------------------------------
//---------------update
public function updateTicketType3(Request $request)
{   $rules = ['id' => 'required', 'ticket_no'  =>  'required', ];
try {
    $user = JWTAuth::parseToken()->authenticate();
    $this->validate($request, $rules);
    $gett1 = $this->TicketType3Service->updateTicketType3Table($request);
   // $this->TicketTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
    return response()->json(compact('gett1'));
} catch (Exception $e) {
    return $this->handleValidationException($e, $this->TicketType3Service, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
}
}

//-----------------delete
public function deleteTicketType3(Request $request)
{
    $rules = [ 'id'  =>  'required',   ];
    try {  $user = JWTAuth::parseToken()->authenticate();    $this->validate($request, $rules);
           $gett1 = $this->TicketType3Service->deleteTicketType3Table($request);
          // $this->TicketTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
           return response()->json(compact('gett1'));
        }
    catch (Exception $e)
    { return $this->handleValidationException($e, $this->TicketType3Service, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
    }
}

    //----------------------pagination-------
    public function getByPaginate(Request $request)
    {
        try {
            // 1) first get user from token to check validation
             $user = JWTAuth::parseToken()->authenticate();
            // 2) get all permissions
             $tt= $this->TicketType3Service->getByPaginate($request);
            // $tt= $this->ticketcsService->getlastcsticket($request);
            
            return response()->json($tt);
        } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500);  }
    }

}
