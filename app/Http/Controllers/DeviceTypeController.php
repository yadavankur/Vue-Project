<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\DeviceTypeService;
use App\Models\Services\MenuService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeviceTypeController extends Controller
{
    private $urlPath = '/devicetypes';
    protected $deviceTypeService;
    protected $userService;
    protected $menuService;

    public function __construct(DeviceTypeService $deviceTypeService,
                                MenuService $menuService,
                                UserService $userService)
    {
        $this->deviceTypeService = $deviceTypeService;
        $this->userService = $userService;
        $this->menuService = $menuService;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getDeviceTypeNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // $user = User::find(5);
//            $canAccess = $this->isAccessible($user, $this->urlPath, $this->menuService);
//            if (!$canAccess)
//            {
//                // no access
//                return response()->json(['error' => 'You have no access permission to this page.'], 444);
//            }
            // 2) get all deviceTypes
            $deviceTypeNodes= $this->deviceTypeService->getByPaginate($request);
            return response()->json($deviceTypeNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateDeviceType(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $deviceType = $this->deviceTypeService->update($request);
            $this->deviceTypeService->LogEntity($deviceType, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('deviceType'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->deviceTypeService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addDeviceType(Request $request)
    {
        $rules = [
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $deviceType = $this->deviceTypeService->add($request);
            $this->deviceTypeService->LogEntity($deviceType, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('deviceType'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->deviceTypeService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteDeviceType(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $deviceType = $this->deviceTypeService->delete($request);
            $this->deviceTypeService->LogEntity($deviceType, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('deviceType'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->deviceTypeService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getDeviceTypeOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $deviceTypes = $this->deviceTypeService->getDeviceTypeOptions($request);
            return response()->json(compact('deviceTypes'));
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
