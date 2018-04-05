<?php

namespace App\Models\Repositories;


use App\Models\Entities\CPM_ServiceGroup;
use Exception;
use Illuminate\Support\Facades\Auth;
use DB;

class CpmServiceGroupRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\CPM_ServiceGroup';
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;

        $filter = $request->filter;
        $locationId = $filter['location']['id'];
        $serviceId = $filter['service']['id'];
        $search = $filter['filterText'];

        $query = $this->model->select(['cpm_service_groups.*'])
            ->join('cpm_services', 'cpm_service_groups.service_id', '=', 'cpm_services.id')
            ->join('locations', 'locations.id', '=', 'cpm_services.location_id')
            ->orderBy($sortBy, $sortDirection)
            ->where('cpm_service_groups.active', 1)
            ->where('locations.active', 1)
            ->where('cpm_services.active', 1)
            ->with('service')->with('service_group_activities');
        if ($serviceId)
        {
            $query->where('cpm_service_groups.service_id', $serviceId);
        }
        else
        {
            $user = Auth::user();
            $locationIds = LocationRepository::getAccessibleLocationIds($user);
            $query->wherein('locations.id', $locationIds);
        }

        if ($search) {
            $like = "%{$search}%";

            $query = $query->where(function ($query) use ($like) {
                $query->where('cpm_service_groups.name', 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
    public function updateCpmServiceGroup($request)
    {
        $type = $request->type;
        $locationId = $request->location['id'];
        $serviceId = $request->service['id'];
        $name = $request->name;
        $comment = $request->comment;
        $id = $request->id;
        $serviceGroup = null;

        switch ($type)
        {
            case 'Add':
                $serviceGroup = new CPM_ServiceGroup();
                $serviceGroup->name = $name;
                $serviceGroup->service_id = $serviceId;
                $serviceGroup->comment = $comment;
                $serviceGroup->save();
                break;
            case 'Edit':
                $serviceGroup = CPM_ServiceGroup::where('id', $id)->active()->first();
                if (!$serviceGroup instanceof CPM_ServiceGroup)
                {
                    throw new Exception('The service group is invalid!');
                }
                $serviceGroup->name = $name;
                $serviceGroup->service_id = $serviceId;
                $serviceGroup->comment = $comment;
                $serviceGroup->save();
                break;
            case 'Delete':
                $serviceGroup = CPM_ServiceGroup::where('id', $id)->active()->first();
                if (!$serviceGroup instanceof CPM_ServiceGroup)
                {
                    throw new Exception('The service group is invalid!');
                }
                $serviceGroup->active = false;
                $serviceGroup->save();
        }
        return $serviceGroup;
    }
    public function getServiceGroupOptions($request)
    {
        $serviceId = $request->serviceId;
        $serviceGroups = $this->model->select([DB::raw("id AS value"), DB::raw("name AS label")])
            ->where('active',1)
            ->where('service_id', $serviceId)
            ->orderBy('id', 'asc')->get();
        return $serviceGroups;
    }
}