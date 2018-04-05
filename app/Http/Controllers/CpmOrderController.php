<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Repositories\CpmOrderMatrixRepository;
use App\Models\Services\CpmOrderMatrixService;
use App\Models\Services\CpmOrderService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CpmOrderController extends Controller
{
    protected $cpmOrderService;
    protected $cpmOrderMatrixService;
    public function __construct(CpmOrderService $cpmOrderService,
                                CpmOrderMatrixService $cpmOrderMatrixService)
    {
        $this->cpmOrderService = $cpmOrderService;
        $this->cpmOrderMatrixService = $cpmOrderMatrixService;
    }
    public function getAssociatedTasks(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get paginated data
            $orderTasks = $this->cpmOrderMatrixService->getAssociatedTasks($request);
            return response()->json($orderTasks);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getOrderActivities(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get paginated data
            // $cpmOrderActivities = $this->cpmOrderService->getByPaginate($request);
            $cpmOrderMatrix = $this->cpmOrderMatrixService->getByPaginate($request);
            return response()->json($cpmOrderMatrix);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getSystemDeliveryDate(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get paginated data
            $systemDeliveryDate = $this->cpmOrderService->getSystemDeliveryDate($request);
            return response()->json($systemDeliveryDate);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function createOrderActivities(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get paginated data
            $activities = $this->cpmOrderService->createOrderActivities($request);
            return response()->json($activities);
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->cpmOrderService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function getAllActivitiesOfOrder(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get paginated data
            $cpmOrderAllActivities = $this->cpmOrderMatrixService->getAllActivitiesOfOrder($request);
            return response()->json($cpmOrderAllActivities);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function calculateCPM(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) simulate the critical path
            // and give latest info back
            $latestCPMInfo = $this->cpmOrderMatrixService->calculateCPM($request);
            return response()->json($latestCPMInfo);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function reCalculateCPM(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) simulate the critical path
            // and give latest info back
            $latestCPMInfo = $this->cpmOrderMatrixService->reCalculateCPM($request);
            return response()->json($latestCPMInfo);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function createCPM(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) calculate the critical path
            // and give latest info back
            $latestCPMInfo = $this->cpmOrderMatrixService->createCPM($request);
            return response()->json($latestCPMInfo);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateCPM(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) calculate the critical path
            // and give latest info back
            $latestCPMInfo = $this->cpmOrderMatrixService->updateCPM($request);
            return response()->json($latestCPMInfo);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function addAdhocActivity(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) calculate the critical path
            // and give latest info back
            $latestCPMInfo = $this->cpmOrderMatrixService->addAdhocActivity($request);
            return response()->json($latestCPMInfo);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getAllTasks(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $tasks = $this->cpmOrderMatrixService->getAllTasks($request);
            return response()->json($tasks);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getTaskOverviewBarChart(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $tasks = CpmOrderMatrixRepository::getTaskOverviewBarChart();
            return response()->json($tasks);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getTaskOverviewDoughnutChart(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $tasks = CpmOrderMatrixRepository::getTaskOverviewDoughnutChart();
            return response()->json($tasks);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
