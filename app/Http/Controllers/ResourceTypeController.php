<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Entities\ResourceType;
use App\Models\Services\ResourceTypeService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ResourceTypeController extends Controller
{
    protected $resourceTypeService;
    public function __construct(ResourceTypeService $resourceTypeService)
    {
        $this->resourceTypeService = $resourceTypeService;
    }
    public function getResourceTypeOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $resourceTypes = $this->resourceTypeService->getResourceTypeOptions($request);
            return response()->json(compact('resourceTypes'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * @param Request $request
     * @return array|null
     */
    public function getResourceTypeNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all resourceTypes
            $resourceTypeNodes= $this->resourceTypeService->getByPaginate($request);
            return response()->json($resourceTypeNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateResourceType(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $resourceType = $this->resourceTypeService->update($request);
            $this->resourceTypeService->LogEntity($resourceType, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('resourceType'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->resourceTypeService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addResourceType(Request $request)
    {
        $rules = [
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $resourceType = $this->resourceTypeService->add($request);
            $this->resourceTypeService->LogEntity($resourceType, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('resourceType'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->resourceTypeService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteResourceType(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $resourceType = $this->resourceTypeService->delete($request);
            $this->resourceTypeService->LogEntity($resourceType, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('resourceType'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->resourceTypeService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
