<?php

namespace App\Http\Controllers;

use App\Models\Services\ResourceService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ResourceController extends Controller
{
    protected $resourceService;
    public function __construct(ResourceService $resourceService)
    {
        $this->resourceService = $resourceService;
    }
    public function getResourceOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $resources = $this->resourceService->getResourceOptions($request);
            return response()->json(compact('resources'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getResourceName(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $resourceName = $this->resourceService->getResourceName($request);
            return response()->json(compact('resourceName'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getCascadeResourceOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $cascadeResources = $this->resourceService->getCascadeResourceOptions($request);
            return response()->json(compact('cascadeResources'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
