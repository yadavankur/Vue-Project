<?php

namespace App\Http\Controllers;


use App\Models\Services\OrderListService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderListController extends Controller
{

    protected $orderListService;
    protected $userService;

    public function __construct(OrderListService $orderListService,
                                UserService $userService)
    {
        $this->orderListService = $orderListService;
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderList(Request $request)
    { try 
        {   $user = JWTAuth::parseToken()->authenticate();// 1) first get user from token to check validation
            $orderList= $this->orderListService->getByPaginate($request, $this->userService);// 2) get all menus
            return response()->json($orderList);
        }   catch (Exception $e) 
        {   return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getStatusOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $statusOptions = $this->orderListService->getStatusOptions($request);
            return $statusOptions;
        }
        catch (Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getOrderDetailOnSearch(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get data
            $selectedOrder= $this->orderListService->getOrderDetailOnSearch($request, $this->userService);
            return response()->json($selectedOrder);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}