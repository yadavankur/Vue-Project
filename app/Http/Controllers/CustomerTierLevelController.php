<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\CustomerTierLevelService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerTierLevelController extends Controller
{
    protected $customerTierLevelService;
    protected $userService;

    public function __construct(CustomerTierLevelService $customerTierLevelService,
                                UserService $userService)
    {
        $this->customerTierLevelService = $customerTierLevelService;
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerTierLevelNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all menus
            $customerTierLevel= $this->customerTierLevelService->getByPaginate($request);
            return response()->json($customerTierLevel);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateCustomerTierLevel(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $customerTierLevel = $this->customerTierLevelService->update($request);
            $this->customerTierLevelService->LogEntity($customerTierLevel, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('customerTierLevel'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->customerTierLevelService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addCustomerTierLevel(Request $request)
    {
        $rules = [
            'name'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $customerTierLevel = $this->customerTierLevelService->add($request);
            $this->customerTierLevelService->LogEntity($customerTierLevel, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('customerTierLevel'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->customerTierLevelService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteCustomerTierLevel(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $customerTierLevel = $this->customerTierLevelService->delete($request);
            $this->customerTierLevelService->LogEntity($customerTierLevel, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('customerTierLevel'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->customerTierLevelService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }

    public function getCustomerTierLevelOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $customerTierLevelOptions = $this->customerTierLevelService->getCustomerTierLevelOptions();
            return $customerTierLevelOptions;
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
