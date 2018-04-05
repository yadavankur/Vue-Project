<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\AddressService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AddressController extends Controller
{
    protected $addressService;
    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }
    public function changeAddress(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            $customerAddress= $this->addressService->changeAddress($request);
            //$this->addressService->LogEntity($customerContact, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($customerAddress);
        } catch (Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500);
            return $this->handleValidationException($e, $this->addressService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
