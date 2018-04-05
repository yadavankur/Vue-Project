<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Entities\ResourceType;
use App\Models\Entities\User;
use App\Models\Services\LocationService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class LocationController extends Controller
{
    protected $locationService;
    protected $userService;

    public function __construct(LocationService $locationService, UserService $userService)
    {
        $this->locationService = $locationService;
        $this->userService = $userService;
    }

    public function show(Request $request)
    {
        // 1) first get user from token to check validation
        $user = JWTAuth::parseToken()->authenticate();

        // 2) get all locations
        $locations = $this->locationService->getAll($user);

        // 2) get accessible locations based on user
        $aclLocations = $this->userService->getAclResourceByType($user, ResourceType::LOCATION);

        // 3) rebuild locations
        $mergedLocations = array_replace($locations, $aclLocations);

        // 4) return locations
        return response()->json([
            'locations' => $mergedLocations,
        ]);
    }
    /**
     * @param Request $request
     * @return array|null
     */
    public function getLocationNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all locations
            $locationNodes= $this->locationService->getByPaginate($request);
            return response()->json($locationNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateLocation(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
            'abbreviation'  =>  'required',
            'state.name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $location = $this->locationService->update($request);
            $this->locationService->LogEntity($location, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('location'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->locationService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addLocation(Request $request)
    {
        $rules = [
            'name'  =>  'required',
            'abbreviation'  =>  'required',
            'state.name' => 'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $location = $this->locationService->add($request);
            $this->locationService->LogEntity($location, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('location'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->locationService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteLocation(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $location = $this->locationService->delete($request);
            $this->locationService->LogEntity($location, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('location'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->locationService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getLocationOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $locations = $this->locationService->getLocationOptions();
            return response()->json(compact('locations'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAssignedLocationOptions(Request $request)
    {try 
        { $user = JWTAuth::parseToken()->authenticate();
          $locations = $this->getAssignedResources($user, $this->userService, $this->locationService,ResourceType::LOCATION);
          return $locations;
        }
        catch (Exception $e)
        { return response()->json(['error' => $e->getMessage()], 500);  }
    }
}
