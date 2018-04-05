<?php

namespace App\Models\Services;

use App\Models\Entities\BusinessCalendarHoliday;
use App\Models\Repositories\BusinessCalendarHolidayRepository;

class BusinessCalendarHolidayService extends BaseService
{
    private $businessCalendarHolidayRepository;
    public function __construct(BusinessCalendarHolidayRepository $businessCalendarHolidayRepository)
    {
        $this->businessCalendarHolidayRepository = $businessCalendarHolidayRepository;
    }
    public static function getPublicHolidays($stateId, $year)
    {
        $years = array($year -1, $year, $year+1);
        $publicHolidays = BusinessCalendarHoliday::where('type', BusinessCalendarHoliday::TYPE_PUBLIC_HOLIDAY)
            ->where('state_id', $stateId)
            ->wherein('year', $years)
            ->where('active', 1)->get();
        return $publicHolidays->pluck('value')->all();
    }
    public function getByPaginate($request)
    {
        return $this->businessCalendarHolidayRepository->getByPaginate($request);
    }
    public function getBusinessCalendarTypes($request)
    {
        $type = $request->type;
        return BusinessCalendarHoliday::getTypes($type);
    }
    public function getBusinessCalendarFilterOptions($request)
    {
        $type = $request->type;
        $types = BusinessCalendarHoliday::getTypes($type);
        $years = $this->businessCalendarHolidayRepository->getYears();
        return array(
            'types' => $types,
            'years' => $years,
        );
    }
    public function saveBusinessCalendar($request)
    {
        return $this->businessCalendarHolidayRepository->saveBusinessCalendar($request);
    }
    public function updateBusinessCalendar($request)
    {
        return $this->businessCalendarHolidayRepository->updateBusinessCalendar($request);
    }
    public function deleteBusinessCalendar($request)
    {
        return $this->businessCalendarHolidayRepository->deleteBusinessCalendar($request);
    }
}