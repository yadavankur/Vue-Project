<?php

namespace App\Http\Controllers;

use App\Models\Entities\CPM_OrderMatrix;
use App\Models\Entities\Location;
use App\Models\Entities\Log;
use App\Models\Entities\ResourceType;
use App\Models\Entities\User;
use App\Models\Services\CpmServiceService;
use App\Models\Services\LocationService;
use App\Models\Services\MenuService;
use App\Models\Services\StateService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CpmServiceController extends Controller
{
    private $urlPath = '/cpmservices';
    protected $cpmServiceService;
    protected $userService;
    protected $menuService;
    protected $stateService;
    protected $locationService;

    public function __construct(CpmServiceService $cpmServiceService,
                                StateService $stateService,
                                MenuService $menuService,
                                LocationService $locationService,
                                UserService $userService)
    {
        $this->cpmServiceService = $cpmServiceService;
        $this->userService = $userService;
        $this->menuService = $menuService;
        $this->stateService = $stateService;
        $this->locationService = $locationService;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getCpmServiceNodes(Request $request)
    {
        try {  // 1) first get user from token to check validation
               $user = JWTAuth::parseToken()->authenticate();
               // 2) get all cpmServices
               $cpmServiceNodes= $this->cpmServiceService->getByPaginate($request);
               return response()->json($cpmServiceNodes);
            } catch (Exception $e) {   return response()->json(['error' => $e->getMessage()], 500);  }
    }
    public function updateCpmService(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
            'state.id' => 'required',
            'state.name' => 'required',
            'location.id' => 'required',
            'location.name' => 'required'
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $cpmService = $this->cpmServiceService->update($request);
            $this->cpmServiceService->LogEntity($cpmService, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('cpmService'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->cpmServiceService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addCpmService(Request $request)
    {
        $rules = [
            'name'  =>  'required',
            'state.id' => 'required',
            'state.name' => 'required',
            'location.id' => 'required',
            'location.name' => 'required'
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $cpmService = $this->cpmServiceService->add($request);
            $this->cpmServiceService->LogEntity($cpmService, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('cpmService'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->cpmServiceService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteCpmService(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $cpmService = $this->cpmServiceService->delete($request);
            $this->cpmServiceService->LogEntity($cpmService, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('cpmService'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->cpmServiceService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getCpmServiceOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $services = $this->cpmServiceService->getCpmServiceOptions($request);
            return response()->json(compact('services'));
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
    public function getCpmCascadeServiceGroupOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $serviceOptions = $this->getAssignedResources(
                                $user,
                                $this->userService,
                                $this->locationService,
                                ResourceType::LOCATION);
            $locationServiceGroups = $this->cpmServiceService->getCpmCascadeServiceGroupOptions($serviceOptions);
            return response()->json(compact('locationServiceGroups'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getCpmCascadeServiceOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $serviceOptions = $this->getAssignedResources($user,
                                $this->userService,
                                $this->locationService,
                                ResourceType::LOCATION);
            $locationServices = $this->cpmServiceService->getCpmCascadeServiceOptions($serviceOptions);
            return response()->json(compact('locationServices'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getCpmServiceOptionsOfLocation(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $services = $this->cpmServiceService->getCpmServiceOptionsOfLocation($request);
            return response()->json(compact('services'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getCpmServiceGroupOptionsOfLocation(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $serviceGroupOptions = $this->cpmServiceService->getCpmServiceGroupOptionsOfLocation($request);
            return response()->json(compact('serviceGroupOptions'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getStatuses(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $statuses = CPM_OrderMatrix::getStatuses();
            return response()->json(compact('statuses'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
