<?php

namespace App\Models\Repositories;


use App\Models\Entities\CPM_Service;
use App\Models\Entities\Location;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class CpmServiceRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\CPM_Service';
    }
    public function getByPaginate($request)
    {   $sort = $request->sort;    $sort = explode('|', $sort);
        $sortBy = $sort[0];        $sortDirection = $sort[1];
        $perPage = $request->per_page;

        $filter = $request->filter;
        $locationId = $filter['location'];    $search = $filter['filterText'];

        $query = $this->model->select('cpm_services.*')
            ->join('locations', 'locations.id', '=', 'cpm_services.location_id')
            ->orderBy($sortBy, $sortDirection)
            ->where('cpm_services.active',1)
            ->with('location');
        if ($locationId) {  $query->where('locations.id', $locationId);  }
        else             {  $user = Auth::user();
                            $locationIds = LocationRepository::getAccessibleLocationIds($user);
                            $query->wherein('locations.id', $locationIds);
                         }

        if ($search) {     $like = "%{$search}%";
                           $query = $query->where(function ($query) use ($like) {
                           $query->where('cpm_services.name', 'LIKE', $like);
                           });
                     }
        return $query->paginate($perPage);
    }

    
    public function add($request)
    {
        $cpmService =  new CPM_Service();
        $cpmService->name = $request->input('name');
        $cpmService->comment = $request->input('comment');
        $locationName = $request->input('location.name');
        $location = Location::where('name', $locationName)->where('active',1)->first();
        if (! $location instanceof Location)
        {
            throw new Exception('The location is invalid!');
        }
        $cpmService->location_id =$location->id;
        $cpmService->save();
        return $cpmService;
    }
    public function save($request)
    {
        $cpmService =  $this->model->findOrFail($request->input('id'));
        $cpmService->name = $request->input('name');
        $locationName = $request->input('location.name');
        $location = Location::where('name', $locationName)->where('active',1)->first();
        if (! $location instanceof Location)
        {
            throw new Exception('The location is invalid!');
        }
        $cpmService->location_id =$location->id;
        $cpmService->comment = $request->input('comment');
        $cpmService->save();
        return $cpmService;
    }
    public function delete($request)
    {
        $cpmService =  $this->model->findOrFail($request->input('id'));
        $cpmService->active = 0;
        $cpmService->save();
        return $cpmService;
    }
    public function getCpmCascadeServiceOptions($locations)
    {
        // get assigned locations service options
        $cascadeElements = [];
        foreach($locations as $location)
        {
            if (count($location['services']) == 0) continue;
            $obj = new \stdClass();
            $obj->value = $location['id'];
            $obj->label = $location['name'];
            $obj->children = [];
            foreach($location['services'] as $service)
            {
                $child = new \stdClass();
                $child->value = $service['id'];
                $child->label = $service['name'];
                $child->children = [];
                $obj->children[] = $child;
            }
            $cascadeElements[] = $obj;
        }
        return $cascadeElements;
    }
    public function getCpmCascadeServiceGroupOptions($locations)
    {
        $cascadeElements = [];
        foreach($locations as $location)
        {
            if (count($location['services']) == 0) continue;
            $obj = new \stdClass();
            $obj->value = $location['id'];
            $obj->label = $location['name'];
            $obj->children = [];
            foreach($location['services'] as $service)
            {
                $child = new \stdClass();
                $child->value = $service['id'];
                $child->label = $service['name'];
                $child->children = [];
                foreach($service['service_groups'] as $service_group)
                {
                    $childGroup = new \stdClass();
                    $childGroup->value = $service_group['id'];
                    $childGroup->label = $service_group['name'];
                    $childGroup->children = [];
                    $child->children[] = $childGroup;
                }
                $obj->children[] = $child;
            }
            $cascadeElements[] = $obj;
        }
        return $cascadeElements;
    }
    public function getCpmServiceOptionsOfLocation($request)
    {
        $locationAbbreviation = $request->location;
        $location = Location::where('abbreviation', $locationAbbreviation)->where('active',1)->first();
        if (! $location instanceof Location)
        {
            throw new Exception('The location is invalid!');
        }
        $services = $this->model->select([DB::raw("id AS value"), DB::raw("name AS label")])
            ->where('active',1)
            ->where('location_id', $location->id)
            ->orderBy('id', 'asc')->get();
        return $services;
    }
    public function getCpmServiceGroupOptionsOfLocation($request)
    {
        $locationAbbreviation = $request->location;
        $location = Location::where('abbreviation', $locationAbbreviation)->where('active',1)->first();
        if (! $location instanceof Location)
        {
            throw new Exception('The location is invalid!');
        }
        $services = $location->services;
        $cascadeElements = [];

        foreach($services as $service)
        {
            $obj = new \stdClass();
            $obj->value = $service->id;
            $obj->label = $service->name;
            $obj->children = [];
            foreach($service->service_groups as $service_group)
            {
                $child = new \stdClass();
                $child->value = $service_group->id;
                $child->label = $service_group->name;
                $child->children = [];
                $obj->children[] = $child;
            }
            $cascadeElements[] = $obj;
        }
        return $cascadeElements;
    }
}