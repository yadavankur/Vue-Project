<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\BusinessCalendarHolidayService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class BusinessCalendarController extends Controller
{
    protected $businessCalendarHolidayService;
    public function __construct(BusinessCalendarHolidayService $businessCalendarHolidayService)
    {
        $this->businessCalendarHolidayService = $businessCalendarHolidayService;
    }

    public function getBusinessCalendarList(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $businessCalendars = $this->businessCalendarHolidayService->getByPaginate($request);
            return response()->json($businessCalendars);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getBusinessCalendarTypes(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $types = $this->businessCalendarHolidayService->getBusinessCalendarTypes($request);
            return response()->json($types);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getBusinessCalendarFilterOptions(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $options = $this->businessCalendarHolidayService->getBusinessCalendarFilterOptions($request);
            return response()->json($options);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function saveBusinessCalendar(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $calendar = $this->businessCalendarHolidayService->saveBusinessCalendar($request);
            $this->businessCalendarHolidayService->LogEntity($calendar, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($calendar);
        } catch (Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500);
            return $this->handleValidationException($e, $this->businessCalendarHolidayService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function updateBusinessCalendar(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $calendar = $this->businessCalendarHolidayService->updateBusinessCalendar($request);
            $this->businessCalendarHolidayService->LogEntity($calendar, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($calendar);
        } catch (Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500);
            return $this->handleValidationException($e, $this->businessCalendarHolidayService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
    public function deleteBusinessCalendar(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $calendar = $this->businessCalendarHolidayService->deleteBusinessCalendar($request);
            $this->businessCalendarHolidayService->LogEntity($calendar, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($calendar);
        } catch (Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500);
            return $this->handleValidationException($e, $this->businessCalendarHolidayService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }
}
