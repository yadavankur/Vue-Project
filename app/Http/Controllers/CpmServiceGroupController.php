<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\CpmServiceGroupService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CpmServiceGroupController extends Controller
{
    protected $cpmServiceGroupService;
    public function __construct(CpmServiceGroupService $cpmServiceGroupService)
    {
        $this->cpmServiceGroupService = $cpmServiceGroupService;
    }
    public function getCpmServiceGroupNodes(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $cpmServiceGroupNodes= $this->cpmServiceGroupService->getByPaginate($request);
            return response()->json($cpmServiceGroupNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateCpmServiceGroup(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $serviceGroup = $this->cpmServiceGroupService->updateCpmServiceGroup($request);
            $this->cpmServiceGroupService->LogEntity($serviceGroup, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($serviceGroup);
        } catch (Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500);
            return $this->handleValidationException($e, $this->cpmServiceGroupService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getServiceGroupOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $serviceGroups = $this->cpmServiceGroupService->getServiceGroupOptions($request);
            return response()->json(compact('serviceGroups'));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
