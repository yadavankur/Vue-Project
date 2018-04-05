<?php

namespace App\Models\Repositories;


use App\Models\Entities\CPM_Activity;
use App\Models\Entities\CPM_Master;
use App\Models\Entities\CPM_Service;
use DB;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class CpmMasterRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\CPM_Master';
    }
    public function getByPaginate($request, $userService)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;

        $search = $request->filter;
        $serviceId = trim($request->service);

        $query = $this->model->select('cpm_masters.*')
            ->join('cpm_activities', 'cpm_masters.activity_id', '=', 'cpm_activities.id')
            ->leftJoin(DB::raw('cpm_activities as predecessor'), 'cpm_masters.predecessor_id', '=', DB::raw('predecessor.id'))
            ->leftJoin(DB::raw('cpm_activities as successor'), 'cpm_masters.successor_id', '=', DB::raw('successor.id'))
            ->orderBy($sortBy, $sortDirection)
            ->where('cpm_activities.active',1)
            ->where('cpm_masters.active',1)
            ->with('service')
            ->with('activity')
            ->with('predecessor')
            ->with('successor');

        if ($search) {
            $like = "%{$search}%";

            $query = $query->where(function ($query) use ($like) {
                $query->where('cpm_activities.name', 'LIKE', $like)
                    ->orWhere(DB::raw('predecessor.name'),'LIKE', $like)
                    ->orWhere(DB::raw('successor.name'),'LIKE', $like);
            });
        }
        if ($serviceId) {
            $query = $query->where(function ($query) use ($serviceId) {
                $query->where('cpm_masters.service_id', $serviceId);
            });
        }
        else
        {
            // get accessible locations
            $user = JWTAuth::parseToken()->authenticate();
            $locationIds = LocationRepository::getAccessibleLocationIds($user, $userService);
            $query->join('cpm_services', 'cpm_services.id', '=', 'cpm_activities.service_id')
                    ->join('locations', 'locations.id', '=', 'cpm_services.location_id')
                    ->wherein('locations.id', $locationIds);

        }
        return $query->paginate($perPage);
    }
    public function add($request)
    {
        $cpmMaster =  new CPM_Master();
        $serviceId = trim($request->input('service.id'));
        $activityId = trim($request->input('activity.id'));
        $predecessorId = trim($request->input('predecessor.id'));
        $successorId = trim($request->input('successor.id'));
        $cpmMaster->comment = $request->input('comment');
        $service = CPM_Service::where('id', $serviceId)->where('active',1)->first();
        if (! $service instanceof CPM_Service)
        {
            throw new Exception('The service is invalid!');
        }
        $cpmMaster->service_id = $service->id;
        $activity = CPM_Activity::where('id', $activityId)->where('active',1)->first();
        if (! $activity instanceof CPM_Activity)
        {
            throw new Exception('The activity is invalid!');
        }
        $cpmMaster->activity_id = $activity->id;
        if ($predecessorId)
        {
            $predecessor = CPM_Activity::where('id', $predecessorId)->where('active',1)->first();
            if (! $predecessor instanceof CPM_Activity)
            {
                throw new Exception('The activity is invalid!');
            }
            $cpmMaster->predecessor_id = $predecessor->id;
        }
        else
            $cpmMaster->predecessor_id = null;
        if ($successorId)
        {
            $successor = CPM_Activity::where('id', $successorId)->where('active',1)->first();
            if (! $successor instanceof CPM_Activity)
            {
                throw new Exception('The activity is invalid!');
            }
            $cpmMaster->successor_id =$successor->id;
        }
        else
            $cpmMaster->successor_id = null;
        // check whether has loop
        if ($cpmMaster->activity_id == $cpmMaster->predecessor_id)
        {
            throw new Exception('Sorry, you cannot set the same activity as your predecessor');
        }
        if ($cpmMaster->activity_id == $cpmMaster->successor_id)
        {
            throw new Exception('Sorry, you cannot set the same activity as your successor');
        }
        if ($cpmMaster->predecessor_id == $cpmMaster->successor_id)
        {
            throw new Exception('Sorry, you cannot set the same activity as both predecessor and successor');
        }
        $cpmMaster->save();
        return $cpmMaster;
    }
    public function save($request)
    {
        $cpmMaster =  $this->model->findOrFail($request->input('id'));
        $serviceId = trim($request->input('service.id'));
        $activityId = trim($request->input('activity.id'));
        $predecessorId = trim($request->input('predecessor.id'));
        $successorId = trim($request->input('successor.id'));
        $cpmMaster->comment = $request->input('comment');
        $service = CPM_Service::where('id', $serviceId)->where('active',1)->first();
        if (! $service instanceof CPM_Service)
        {
            throw new Exception('The service is invalid!');
        }
        $cpmMaster->service_id = $service->id;
        $activity = CPM_Activity::where('id', $activityId)->where('active',1)->first();
        if (! $activity instanceof CPM_Activity)
        {
            throw new Exception('The activity is invalid!');
        }
        $cpmMaster->activity_id = $activity->id;
        if ($predecessorId)
        {
            $predecessor = CPM_Activity::where('id', $predecessorId)->where('active',1)->first();
            if (! $predecessor instanceof CPM_Activity)
            {
                throw new Exception('The activity is invalid!');
            }
            $cpmMaster->predecessor_id =$predecessor->id;
        }
        else
            $cpmMaster->predecessor_id = null;
        if ($successorId)
        {
            $successor = CPM_Activity::where('id', $successorId)->where('active',1)->first();
            if (! $successor instanceof CPM_Activity)
            {
                throw new Exception('The activity is invalid!');
            }
            $cpmMaster->successor_id =$successor->id;
        }
        else
            $cpmMaster->successor_id = null;
        // check whether has loop
        if ($cpmMaster->activity_id == $cpmMaster->predecessor_id)
        {
            throw new Exception('Sorry, you cannot set the same activity as a predecessor');
        }
        if ($cpmMaster->activity_id == $cpmMaster->successor_id)
        {
            throw new Exception('Sorry, you cannot set the same activity as a successor');
        }
        if ($cpmMaster->predecessor_id == $cpmMaster->successor_id)
        {
            throw new Exception('Sorry, you cannot set the same activity as both predecessor and successor');
        }
        $cpmMaster->save();
        return $cpmMaster;
    }
    public function delete($request)
    {
        $cpmMaster =  $this->model->findOrFail($request->input('id'));
        $cpmMaster->active = 0;
        $cpmMaster->save();
        return $cpmMaster;
    }
}