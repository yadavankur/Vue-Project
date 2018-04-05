<?php
namespace App\Models\Repositories;

use App\Models\Entities\BaseModel;
use App\Models\Entities\cpm1;
//use App\Models\Entities\CPM_Service;
use App\Models\Entities\CPM_ServiceGroupActivity;
use App\Models\Entities\CPMActivityGroup;
use App\Models\Entities\CPMActivityUser;
use App\Models\Entities\User;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class Cpm1Repository extends BaseRepository
{
    public function model()    {    //return 'App\Models\Entities\CPM_Activity';  
                                    return 'App\Models\Entities\cpm1';  
                                    }

    public function getByPaginate($request, $userService)
      {     $sort = $request->sort;    $sort = explode('|', $sort);
            $sortBy = $sort[0];   $sortDirection = $sort[1];
            $perPage = $request->per_page;

            $filter = $request->filter;
            $locationId = $filter['location']['id'];
            $serviceId = $filter['service']['id'];
            $serviceGroupId = $filter['service_group']['id'];
            $search = $filter['filterText'];

           $query = $this->model->select('cpm1s.*')
                                ->join('cpm_services', 'cpm_services.id', '=', 'cpm1s.service_id')
                                ->join('locations', 'cpm_services.location_id', '=', 'locations.id')
                                ->leftjoin('cpm_service_group_activities', 
                                function($join) 
                                {    $join->on('cpm_service_group_activities.activity_id', '=', 'cpm1s.id')
                                     ->where('cpm_service_group_activities.active', 1);
                                })
                               ->leftjoin('cpm_service_groups', 
                               function($join) 
                               {
                               $join->on('cpm_service_groups.id', '=', 'cpm_service_group_activities.service_group_id')
                              ->where('cpm_service_groups.active', 1);
                              })
            ->orderBy($sortBy, $sortDirection)
            ->where('cpm1s.active',1)
            ->where('cpm_services.active',1)
            ->where('locations.active',1)
            ->with('service_group_activity')
            ->with('service');

        if ($search) 
           {  $like = "%{$search}%";
              $query = $query->where(function ($query) use ($like) 
              { // $query->where('cpm_activities.name', 'LIKE', $like)
                $query->where('cpm1s.name', 'LIKE', $like)
                       ->orWhere('cpm_services.name','LIKE', $like);
               });
           }
        if ($serviceGroupId)
           {  $query->where('cpm_service_group_activities.service_group_id', $serviceGroupId); }
        //else if ($serviceId)  {  $query->where('cpm_activities.service_id', $serviceId); }
        else if ($serviceId)  {  $query->where('cpm1s.service_id', $serviceId); }
        else if ($locationId) {  $query->where('locations.id', $locationId);  }
        else
           {
            // get accessible locations
            $user = JWTAuth::parseToken()->authenticate();
            $locationIds = LocationRepository::getAccessibleLocationIds($user, $userService);
            $query->wherein('locations.id', $locationIds);
           }
        return $query->paginate($perPage);
    }
    //-----------------------display finish------delete record start--------------
    public function delete($request)
    {   try {   DB::beginTransaction();
                $cpm1Activity =  $this->model->findOrFail($request->input('id'));
                $cpm1Activity->active = false;
                $cpm1Activity->save();

                $activityId = $cpm1Activity->id;
            // delete from cpm_service_group_activities
                $serviceGroupActivity = CPM_ServiceGroupActivity::where('activity_id', $activityId)->where('active',1)->first();
                if ($serviceGroupActivity instanceof CPM_ServiceGroupActivity)
                  {    $serviceGroupActivity->active = false;
                       $serviceGroupActivity->save();
                  }

               DB::commit();     return $cpm1Activity;
            }
        catch (Exception $e)
            { DB::rollback();  throw $e;  }
    }//delete finish

}