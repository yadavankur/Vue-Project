<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\SystemSettingService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class SystemSettingController extends Controller
{
    protected $systemSettingService;
    public function __construct(SystemSettingService $systemSettingService)
    {
        $this->systemSettingService = $systemSettingService;
    }
    public function getDefaultSettingList(Request $request)
    {
        try {
            // get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            $settings = $this->systemSettingService->getByPaginate($request);
            return response()->json($settings);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request)
    {
        $rules = [
            'action_type' => 'required',
            'type' => 'required',
            'value'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $setting = $this->systemSettingService->update($request);
            $this->systemSettingService->LogEntity($setting, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('setting'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->systemSettingService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getSettingByLocation(Request $request)
    {
        try {
            // get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            $settings = $this->systemSettingService->getSettingByLocation($request);
            return response()->json($settings);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
