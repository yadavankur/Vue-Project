<?php

namespace App\Http\Controllers;

use App\Models\Services\OrderItemService;
use App\Models\Services\UserService;
use App\Models\Services\V6BomExtnService;
use App\Models\Services\V6BomFillService;
use App\Models\Services\V6ComponentService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderItemController extends Controller
{
    protected $orderItemService;
    protected $v6BomFillService;
    protected $v6BomExtnService;
    protected $v6ComponentService;
    protected $userService;

    public function __construct(OrderItemService $orderItemService,
                                V6BomFillService $v6BomFillService,
                                V6BomExtnService $v6BomExtnService,
                                V6ComponentService $v6ComponentService,
                                UserService $userService)
    {
        $this->orderItemService = $orderItemService;
        $this->v6BomFillService = $v6BomFillService;
        $this->v6BomExtnService = $v6BomExtnService;
        $this->v6ComponentService = $v6ComponentService;
        $this->userService = $userService;
    }
    public function getOrderItems(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the order detail
            // $orderItems= $this->orderItemService->getOrderItems($request);
            $orderItems= $this->orderItemService->getByPaginate($request);
            return response()->json($orderItems);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getBomFills(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the bom detail
            $bomFills= $this->v6BomFillService->getByPaginate($request);
            return response()->json($bomFills);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getBomFinishes(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the bom detail
            $bomFinishes = $this->v6BomExtnService->getByPaginate($request);
            return response()->json($bomFinishes);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getBomComponents(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get the bom detail
            $v6Components= $this->v6ComponentService->getByPaginate($request);
            return response()->json($v6Components);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
