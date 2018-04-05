<?php

namespace App\Models\Repositories;

use App\Models\Entities\Location;
use App\Models\Entities\SystemSetting;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class SystemSettingRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\SystemSetting';
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;
        $search = $request->input('filter.filterText');
        $locationId = $request->input('filter.location.id');

        $query = $this->model->select()
            ->active()
            ->with('location')
            ->orderBy($sortBy, $sortDirection);

        if ($locationId) {
            $query->where('system_settings.location_id', $locationId);
        }
        else
        {
            // get accessible locations
            $user = JWTAuth::parseToken()->authenticate();
            $locationIds = LocationRepository::getAccessibleLocationIds($user);
            $query->wherein('system_settings.location_id', $locationIds);
        }

        if ($search) {
            $like = "%{$search}%";
            $query->where('system_settings.type', 'LIKE', $like);
        }
        return $query->paginate($perPage);
    }
    public function save($request)
    {
        $action_type = $request->input('action_type');
        $id = $request->input('id');
        $locationId = $request->input('location_id');
        $type = $request->input('type');
        $value = $request->input('value');
        $description = $request->input('description');
        $setting = null;

        switch ($action_type)
        {
            case 'updated':
                $setting = $this->model->findOrFail($id);
                if (!$setting instanceof SystemSetting)
                {
                    throw new Exception('Invalid setting!');
                }

                $existedSystemSetting = SystemSetting::where('type', $type)
                    ->where('location_id', $locationId)
                    ->where('id', '<>',  $id)->active()->first();
                if ($existedSystemSetting instanceof SystemSetting)
                {
                    throw new Exception("Sorry, $type has already existed!");
                }
                $setting->type = $type;
                $setting->value = $value;
                $setting->description = $description;
                $setting->save();
                break;
            case 'added':
                // first check whether there are types in the database with the same name
                $setting = SystemSetting::where('type', $type)
                    ->where('location_id', $locationId)
                    ->active()->first();
                if ($setting instanceof SystemSetting)
                {
                    throw new Exception("Sorry, $type has already existed!");
                }
                $setting = new SystemSetting();
                $setting->location_id = $locationId;
                $setting->type = $type;
                $setting->value = $value;
                $setting->description = $description;
                $setting->active = 1;
                $setting->save();
                break;
            case 'deleted':
                $setting = $this->model->findOrFail($id);
                if (!$setting instanceof SystemSetting)
                {
                    throw new Exception('Invalid setting!');
                }
                $setting->active = 0;
                $setting->save();
                break;
        }

        return $setting;
    }
    public function getSettingByLocation($request)
    {
        $locationAbbr = $request->input('location');
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;
        $query = $this->model->select()
            ->active()->where('system_settings.location_id', $locationId);
        $settings = $query->get();
        return $settings;
    }
}