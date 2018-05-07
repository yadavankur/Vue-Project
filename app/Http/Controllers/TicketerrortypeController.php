<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Entities\Log;
use App\Models\Services\TicketErrorTypeService;
use App\Models\Services\UserService;

use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class TicketerrortypeController extends Controller
{
    //
    protected $TicketErrorTypeService; protected $userService; 
    public function __construct( UserService $userService, TicketErrorTypeService $TicketErrorTypeService)
    {   $this->TicketErrorTypeService = $TicketErrorTypeService;   
        $this->userService = $userService; 
       
    }

    public function getTicketErrortype(Request $request)
    {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
                 $gett1= $this->TicketErrorTypeService->getTicketErrorTypeTable();// 2) get all states
                
                 return $gett1;
             } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
    }
//-------------------------------------------------------------
    public function addTicketErrortype(Request $request)
    {   $rules = [ 'STATUS'  =>  'required',  ];
        try {    $user = JWTAuth::parseToken()->authenticate();
                 $this->validate($request, $rules);
                 $gett1 = $this->TicketErrorTypeService->addTicketErrorTypeTable($request);
                 $this->TicketErrorTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
                 return response()->json(compact('gett1'));
            } catch (Exception $e) 
            {    return $this->handleValidationException($e, $this->TicketErrorTypeService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
            }
    }
//------------------------------------------------------------
//---------------update
public function updateTicketErrortype(Request $request)
{   $rules = ['id' => 'required', 'STATUS'  =>  'required', ];
    try {
        $user = JWTAuth::parseToken()->authenticate();
        $this->validate($request, $rules);
        $gett1 = $this->TicketErrorTypeService->updateTicketErrorTypeTable($request);
        $this->TicketErrorTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
        return response()->json(compact('gett1'));
    } catch (Exception $e) {
        return $this->handleValidationException($e, $this->TicketErrorTypeService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
    }
}

    //-----------------delete
    public function deleteTicketErrortype(Request $request)
    {
        $rules = [ 'id'  =>  'required',   ];
        try {  $user = JWTAuth::parseToken()->authenticate();    $this->validate($request, $rules);
               $gett1 = $this->TicketErrorTypeService->deleteTicketErrorTypeTable($request);
               $this->TicketErrorTypeService->LogEntity($gett1, 'success', __CLASS__ . '::' .__FUNCTION__);
               return response()->json(compact('gett1'));
            }
        catch (Exception $e)
        { return $this->handleValidationException($e, $this->TicketErrorTypeService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
