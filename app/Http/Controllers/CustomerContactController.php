<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\V6CustomerContactService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerContactController extends Controller
{
    protected $v6CustomerContactService;
    public function __construct(V6CustomerContactService $v6CustomerContactService)
    {
        $this->v6CustomerContactService = $v6CustomerContactService;
    }
    public function getCustomerContacts(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the associated jobs
            $customerContacts= $this->v6CustomerContactService->getByPaginate($request);
            return response()->json($customerContacts);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function changeSupervisor(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the associated jobs
            $customerContact= $this->v6CustomerContactService->changeSupervisor($request);
            //$this->v6CustomerContactService->LogEntity($customerContact, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($customerContact);
        } catch (Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500);
            return $this->handleValidationException($e, $this->v6CustomerContactService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function newSupervisor(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the associated jobs
            $customerContact= $this->v6CustomerContactService->newSupervisor($request);
            $this->v6CustomerContactService->LogEntity($customerContact, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($customerContact);
        } catch (Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500);
            return $this->handleValidationException($e, $this->v6CustomerContactService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
