<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\CustomerTierService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerTierController extends Controller
{
    protected $customerTierService;
    protected $userService;

    public function __construct(CustomerTierService $customerTierService,
                                UserService $userService)
    {
        $this->customerTierService = $customerTierService;
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerTierNodes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all menus
            $customerTier= $this->customerTierService->getByPaginate($request);
            return response()->json($customerTier);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateCustomerTier(Request $request)
    {
        $rules = [
            'customer.id' => 'required',
            'customer.name' => 'required',
            'customer.code' => 'required',
            'tierLevel'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $customerTier = $this->customerTierService->update($request);
            $this->customerTierService->LogEntity($customerTier, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('customerTier'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->customerTierService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function addCustomerTier(Request $request)
    {
        $rules = [
            'customer.id' => 'required',
            'customer.name' => 'required',
            'customer.code' => 'required',
            'tierLevel'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $customerTier = $this->customerTierService->add($request);
            $this->customerTierService->LogEntity($customerTier, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('customerTier'));
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->customerTierService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteCustomerTier(Request $request)
    {
        $rules = [
            'id'  =>  'required',
        ];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            $customerTier = $this->customerTierService->delete($request);
            $this->customerTierService->LogEntity($customerTier, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json(compact('customerTier'));
        }
        catch (Exception $e)
        {
            return $this->handleValidationException($e, $this->customerTierService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
