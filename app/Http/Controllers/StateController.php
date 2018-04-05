<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\StateService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class StateController extends Controller
{   protected $stateService; protected $userService;
    public function __construct(StateService $stateService, UserService $userService)
    {   $this->stateService = $stateService;  $this->userService = $userService;  }
    /**
     * @param Request $request
     * @return array|null
     */
    public function getStateNodes(Request $request)
    {    try {   $user = JWTAuth::parseToken()->authenticate(); // 1) first get user from token to check validation
                 $stateNodes= $this->stateService->getAllStateNodes();// 2) get all states
                 return response()->json(['stateNodes' => $stateNodes, ]);
             } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
    }
    public function updateState(Request $request)
    {   $rules = ['id' => 'required', 'name'  =>  'required', ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $state = $this->stateService->update($request);
            $this->stateService->LogEntity($state, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('state'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->stateService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addState(Request $request)
    {   $rules = [ 'name'  =>  'required',  ];
        try {    $user = JWTAuth::parseToken()->authenticate();
                 $this->validate($request, $rules);
                 $state = $this->stateService->add($request);
                 $this->stateService->LogEntity($state, 'success', __CLASS__ . '::' .__FUNCTION__);
                 return response()->json(compact('state'));
            } catch (Exception $e) 
            {    return $this->handleValidationException($e, $this->stateService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
            }
    }
    public function deleteState(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $state = $this->stateService->delete($request);
            $this->stateService->LogEntity($state, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('state'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->stateService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getStateOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $states = $this->stateService->getStateOptions();
            return response()->json(compact('states'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getCascadeLocations(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $cascadeLocations = $this->stateService->getCascadeLocations();
            return response()->json(compact('cascadeLocations'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getAssignedCascadeLocations(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $cascadeLocations = $this->stateService->getAssignedCascadeLocationOptions();
            return response()->json(compact('cascadeLocations'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
