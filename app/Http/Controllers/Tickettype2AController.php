<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entities\Log;
//use App\Models\Services\TicketStatusService;
use App\Models\Services\TicketType2AService;
use App\Models\Services\UserService;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class TicketType2AController extends Controller
{
    //
    protected $TicketType2AService; protected $userService; 
    public function __construct( UserService $userService, TicketType2AService $TicketType2AService)
    {   $this->TicketType2AService = $TicketType2AService;   $this->userService = $userService;  }
//------------------------------------------------------------
    public function getTicketType2ATable(Request $request)
    //public function displaycs()
    {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
               // $this->validate($request, $rules);         
                $gett1= $this->TicketType2AService->getTicketType2ATable($request);
               // $gett1= $this->ticketcsService->displaycs();// 2) get all states
                 return response()->json(['gett1' => $gett1, ]);
                // return response()->json($gett1);
               //  return $gett1;
             } catch (Exception $e) 
             { return response()->json(['error' => $e->getMessage()], 500); }
    }
//-------------------------------------------------------------
public function addTicketType2ATable(Request $request)
{   $rules = [// 'price'  =>  'required', 
    'ticket_no' => 'required'  ];
    try {    $user = JWTAuth::parseToken()->authenticate();
             $this->validate($request, $rules);
             $gett1 = $this->TicketType2AService->addTicketType2ATable($request);
           //  $this->TicketTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
           return response()->json(compact('gett1'));
           //  return response()->json(compact('gett1'));
        } catch (Exception $e) 
        {    return $this->handleValidationException($e, $this->TicketType2AService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
}
//------------------------------------------------------------
//---------------update
public function updateTicketType2A(Request $request)
{   $rules = ['id' => 'required', 'ticket_no'  =>  'required', ];
try {
    $user = JWTAuth::parseToken()->authenticate();
    $this->validate($request, $rules);
    $gett1 = $this->TicketType2AService->updateTicketType2ATable($request);
   // $this->TicketTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
    return response()->json(compact('gett1'));
} catch (Exception $e) {
    return $this->handleValidationException($e, $this->TicketType2AService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
}
}

//-----------------delete
public function deleteTicketType2A(Request $request)
{
    $rules = [ 'id'  =>  'required',   ];
    try {  $user = JWTAuth::parseToken()->authenticate();    $this->validate($request, $rules);
           $gett1 = $this->TicketType2AService->deleteTicketType2ATable($request);
          // $this->TicketTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
           return response()->json(compact('gett1'));
        }
    catch (Exception $e)
    { return $this->handleValidationException($e, $this->TicketType2AService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
    }
}

    //----------------------pagination-------
    public function getByPaginate(Request $request)
    {
        try {
            // 1) first get user from token to check validation
             $user = JWTAuth::parseToken()->authenticate();
            // 2) get all permissions
             $tt= $this->TicketType2AService->getByPaginate($request);
            // $tt= $this->ticketcsService->getlastcsticket($request);
            
            return response()->json($tt);
        } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500);  }
    }

}
