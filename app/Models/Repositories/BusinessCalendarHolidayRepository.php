<?php
namespace App\Models\Repositories;

use App\Models\Entities\BusinessCalendarHoliday;
use App\Models\Entities\Location;
use Carbon\Carbon;
use DB;
use Exception;

class BusinessCalendarHolidayRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\BusinessCalendarHoliday';
    }
    public function getByPaginate($request)
    {

        $perPage = $request->per_page;
        $locationId = $request->filter['location'];
        $location = Location::where('id', $locationId)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;
        $search = $request->filter['filterText'];
        $type = $request->filter['type'];
        $year = $request->filter['year'];

        $query = $this->model->select([
            'business_calendar_holidays.*',
        ])
            ->with('location')
            ->join('locations', 'locations.id', '=', 'business_calendar_holidays.location_id')
            ->where('business_calendar_holidays.active', 1)
            ->where('locations.active', 1);

//        $sorts = $request->sort;
//        $sortFields = explode(',', $sorts);
//        foreach($sortFields as $sortField)
//        {
//            $sort = explode('|', $sortField);
//            $sortBy = $sort[0];
//            $sortDirection = $sort[1];
//            $query->orderBy($sortBy, $sortDirection);
//        }
        $sort = $request->sort;
        $sort = explode('|', $sort);
        $sortBy = $sort[0];
        $sortDirection = $sort[1];
        $orderSql =  "CONVERT(DATETIME, [business_calendar_holidays].[value], 103) " . $sortDirection;
        $query->orderByRaw($orderSql);


        if ($locationId)
        {
            $query->where('business_calendar_holidays.location_id', $locationId);
        }
        if ($type)
        {
            $query->where('business_calendar_holidays.type', $type);
        }
        if ($year)
        {
            $query->where('business_calendar_holidays.year', $year);
        }
        if ($search) {
            $like = "%{$search}%";
            $query->where('business_calendar_holidays.description', 'LIKE', $like);
        }
        return $query->paginate($perPage);
    }
    public function getYears()
    {
        $query = $this->model->distinct()->select([
            'business_calendar_holidays.year',
        ])
            ->where('business_calendar_holidays.active', 1)
            ->orderBy('business_calendar_holidays.year', 'asc');
        return $query->get()->pluck('year')->all();
    }
    public function saveBusinessCalendar($request)
    {
        $locationId = $request->location;
        $type = $request->type;
        $businessDate = $request->businessDate;
        $description =$request->description;
        $id = $request->id;

        if ($id)
        {
            $businessCalendar =  BusinessCalendarHoliday::where('id', $id)->active()->first();
            if (!$businessCalendar instanceof BusinessCalendarHoliday)
            {
                throw new Exception('Invalid date!');
            }
        }

        $location = Location::where('id', $locationId)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');

        // validate whether the date has already registered
        //$businessDate = Carbon::createFromFormat('!d/m/Y', $businessDate);
        $calendar = BusinessCalendarHoliday::where('value', $businessDate)
            ->where('location_id', $locationId)
            ->active()
            ->typeIn([BusinessCalendarHoliday::TYPE_WORKING_DAY, BusinessCalendarHoliday::TYPE_NONWORKING_DAY])
            ->first();
        if ($calendar instanceof BusinessCalendarHoliday)
        {
            throw new Exception('The date has already been registered!');
        }
        // register
        $dateArray = explode('/', $businessDate);
        $year = $dateArray[2];
        $newCalendar = new BusinessCalendarHoliday();
        $newCalendar->state_id = $location->state->id;
        $newCalendar->location_id = $location->id;
        $newCalendar->year = $year;
        $newCalendar->type = $type;
        $newCalendar->value = $businessDate;
        $newCalendar->description = $description;
        $newCalendar->active = 1;
        $newCalendar->save();
        return $newCalendar;

    }
    public function updateBusinessCalendar($request)
    {
        $locationId = $request->location;
        $type = $request->type;
        $businessDate = $request->businessDate;
        $description =$request->description;
        $id = $request->id;

        $businessCalendar =  BusinessCalendarHoliday::where('id', $id)->active()->first();
        if (!$businessCalendar instanceof BusinessCalendarHoliday)
        {
            throw new Exception('Invalid date!');
        }

//        $location = Location::where('id', $locationId)->where('active' ,1)->first();
//        if (!$location instanceof Location)
//            throw new Exception('Invalid location!');

        // validate whether the date has already registered
        //$businessDate = Carbon::createFromFormat('!d/m/Y', $businessDate);
        $calendar = BusinessCalendarHoliday::where('value', $businessDate)
            ->where('id', '<>', $id)
            ->where('location_id', $locationId)
            ->active()
            ->typeIn([BusinessCalendarHoliday::TYPE_WORKING_DAY, BusinessCalendarHoliday::TYPE_NONWORKING_DAY])
            ->first();
        if ($calendar instanceof BusinessCalendarHoliday)
        {
            throw new Exception('The date has already been registered!');
        }
        // register
        $dateArray = explode('/', $businessDate);
        $year = $dateArray[2];
        //$businessCalendar->state_id = $location->state->id;
        //$businessCalendar->location_id = $location->id;
        $businessCalendar->year = $year;
        $businessCalendar->type = $type;
        $businessCalendar->value = $businessDate;
        $businessCalendar->description = $description;
        $businessCalendar->active = 1;
        $businessCalendar->save();
        return $businessCalendar;
    }
    public function deleteBusinessCalendar($request)
    {
        $id = $request->id;
        $businessCalendar =  BusinessCalendarHoliday::where('id', $id)->active()->first();
        if (!$businessCalendar instanceof BusinessCalendarHoliday)
        {
            throw new Exception('Invalid date!');
        }
        $businessCalendar->active = 0;
        $businessCalendar->save();
        return $businessCalendar;
    }
}