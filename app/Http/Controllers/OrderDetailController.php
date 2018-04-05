<?php

namespace App\Http\Controllers;

use App\Models\Services\OrderDetailService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderDetailController extends Controller
{
    protected $orderDetailService;
    protected $userService;

    public function __construct(OrderDetailService $orderDetailService,
                                UserService $userService)
    {
        $this->orderDetailService = $orderDetailService;
        $this->userService = $userService;
    }
    public function getDetails(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the order detail
            $orderDetail= $this->orderDetailService->getDetails($request);
            return response()->json([
                'details' => $orderDetail
            ]);
        } catch (Exception $e) {  return response()->json(['error' => $e->getMessage()], 500); }
    }
}
