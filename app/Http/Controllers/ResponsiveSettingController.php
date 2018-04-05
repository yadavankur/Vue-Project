<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\ResponsiveSettingService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ResponsiveSettingController extends Controller
{
    protected $responsiveSettingService;
    protected $userService;

    public function __construct(ResponsiveSettingService $responsiveSettingService, UserService $userService)
    {
        $this->responsiveSettingService = $responsiveSettingService;
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getResponsiveSettingNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all responsiveSettings
            $responsiveSettingNodes= $this->responsiveSettingService->getByPaginate($request);
            return response()->json($responsiveSettingNodes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateResponsiveSetting(Request $request)
    {
        $rules = [
            'id' => 'required',
            'column_name'  =>  'required',
            'component.id' => 'required',
            'component.name' => 'required',
            'device_type.id' => 'required',
            'device_type.name' => 'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $responsiveSetting = $this->responsiveSettingService->update($request);
            $this->responsiveSettingService->LogEntity($responsiveSetting, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('responsiveSetting'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->responsiveSettingService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addResponsiveSetting(Request $request)
    {
        $rules = [
            'column_name'  =>  'required',
            'component.id' => 'required',
            'component.name' => 'required',
            'device_type.id' => 'required',
            'device_type.name' => 'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $responsiveSetting = $this->responsiveSettingService->add($request);
            $this->responsiveSettingService->LogEntity($responsiveSetting, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('responsiveSetting'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->responsiveSettingService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteResponsiveSetting(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $responsiveSetting = $this->responsiveSettingService->delete($request);
            $this->responsiveSettingService->LogEntity($responsiveSetting, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('responsiveSetting'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->responsiveSettingService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
