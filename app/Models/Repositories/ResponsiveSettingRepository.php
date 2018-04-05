<?php

namespace App\Models\Repositories;

use App\Models\Entities\Component;
use App\Models\Entities\DeviceType;
use App\Models\Entities\ResponsiveSetting;
use DB;
use Exception;

class ResponsiveSettingRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\ResponsiveSetting';
    }

    public function getAll()
    {
        return $this->model->where('active',1)->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;

        $search = $request->filter;

        $query = $this->model->select('responsive_settings.*')
            ->join('device_types', 'device_types.id', '=', 'responsive_settings.device_type_id')
            ->join('components', 'components.id', '=', 'responsive_settings.component_id')
            ->join('pages', 'pages.id', '=', 'components.page_id')
            ->orderBy($sortBy, $sortDirection)
            ->where('responsive_settings.active',1)
            ->where('device_types.active',1)
            ->where('components.active',1)
            ->where('pages.active',1)
            ->with('component')->with('device_type');

        if ($search) {
            $like = "%{$search}%";

            $query = $query->where(function ($query) use ($like) {
                $query->where('responsive_settings.column_name', 'LIKE', $like)
                    ->orWhere('components.name','LIKE', $like)
                    ->orWhere('device_types.name','LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
    public function add($request)
    {
        $responsiveSetting =  new ResponsiveSetting();
        $responsiveSetting->column_name = $request->input('column_name');
        $responsiveSetting->comment = $request->input('comment');
        $deviceTypeId = $request->input('device_type.id'); //id
        $deviceType = DeviceType::where('id', $deviceTypeId)->where('active',1)->first();
        if (! $deviceType instanceof DeviceType)
        {
            throw new Exception('The deviceType is invalid!');
        }
        $responsiveSetting->device_type_id =$deviceType->id;

        $componentId = $request->input('component.id'); //id
        $component = Component::where('id', $componentId)->where('active',1)->first();
        if (! $component instanceof Component)
        {
            throw new Exception('The component is invalid!');
        }
        $responsiveSetting->component_id =$component->id;
        $responsiveSetting->save();
        return $responsiveSetting;
    }
    public function save($request)
    {
        $responsiveSetting =  $this->model->findOrFail($request->input('id'));
        $responsiveSetting->column_name = $request->input('column_name');
        $responsiveSetting->comment = $request->input('comment');
        $deviceTypeId = $request->input('device_type.id'); //id
        $deviceType = DeviceType::where('id', $deviceTypeId)->where('active',1)->first();
        if (! $deviceType instanceof DeviceType)
        {
            throw new Exception('The deviceType is invalid!');
        }
        $responsiveSetting->device_type_id =$deviceType->id;

        $componentId = $request->input('component.id'); //id
        $component = Component::where('id', $componentId)->where('active',1)->first();
        if (! $component instanceof Component)
        {
            throw new Exception('The component is invalid!');
        }
        $responsiveSetting->component_id =$component->id;
        $responsiveSetting->save();
        return $responsiveSetting;
    }
    public function delete($request)
    {
        $responsiveSetting =  $this->model->findOrFail($request->input('id'));
        $responsiveSetting->active = 0;
        $responsiveSetting->save();
        return $responsiveSetting;
    }
}