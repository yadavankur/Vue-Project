<?php

namespace App\Models\Repositories;

use App\Models\Entities\BaseModel;
use App\Models\Entities\CPM_Activity;
use App\Models\Entities\CPM_Service;
use App\Models\Entities\CPM_ServiceGroupActivity;
use App\Models\Entities\CPMActivityGroup;
use App\Models\Entities\CPMActivityUser;
use App\Models\Entities\User;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class CpmActivityRepository extends BaseRepository
{
    public function model()  {   return 'App\Models\Entities\CPM_Activity';    }

    public function getByPaginate($request, $userService)
      {     $sort = $request->sort;    $sort = explode('|', $sort);
            $sortBy = $sort[0];   $sortDirection = $sort[1];
            $perPage = $request->per_page;

            $filter = $request->filter;
            $locationId = $filter['location']['id'];
            $serviceId = $filter['service']['id'];
            $serviceGroupId = $filter['service_group']['id'];
            $search = $filter['filterText'];

           $query = $this->model->select('cpm_activities.*')
                                ->join('cpm_services', 'cpm_services.id', '=', 'cpm_activities.service_id')
                                ->join('locations', 'cpm_services.location_id', '=', 'locations.id')
                                ->leftjoin('cpm_service_group_activities', 
                                function($join) 
                                {   $join->on('cpm_service_group_activities.activity_id', '=', 'cpm_activities.id')
                                     ->where('cpm_service_group_activities.active', 1);
                                })
                               ->leftjoin('cpm_service_groups', 
                               function($join) 
                               {
                               $join->on('cpm_service_groups.id', '=', 'cpm_service_group_activities.service_group_id')
                              ->where('cpm_service_groups.active', 1);
                              })
            ->orderBy($sortBy, $sortDirection)
            ->where('cpm_activities.active',1)
            ->where('cpm_services.active',1)
            ->where('locations.active',1)
            ->with('service_group_activity')
            ->with('service');

        if ($search) 
           {  $like = "%{$search}%";
              $query = $query->where(function ($query) use ($like) 
              {  $query->where('cpm_activities.name', 'LIKE', $like)
                       ->orWhere('cpm_services.name','LIKE', $like);
               });
           }
        if ($serviceGroupId)
           {  $query->where('cpm_service_group_activities.service_group_id', $serviceGroupId); }
        else if ($serviceId)  {  $query->where('cpm_activities.service_id', $serviceId); }
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



    public function add($request)
    {
        try {
            DB::beginTransaction();

            $cpmActivity =  new CPM_Activity();
            $cpmActivity->name = $request->input('name');
            $cpmActivity->comment = $request->input('comment');
            $cpmActivity->duration = $request->input('duration');
            $cpmActivity->is_full = $request->input('isFull');
            $cpmActivity->sql_statement = $request->input('sql_statement');
            $cpmActivity->action_sql = $request->input('action_sql');
            $cpmActivity->tick_option = $request->input('tick_option');
            $serviceId = $request->input('service.id');
            $service = CPM_Service::where('id', $serviceId)->where('active',1)->first();
            if (! $service instanceof CPM_Service) {        throw new Exception('The service is invalid!');    }
            $cpmActivity->service_id =$service->id;
            $cpmActivity->save();

            $activityId = $cpmActivity->id;
            $serviceGroupId = $request->input('service_group');
            if ($serviceGroupId)
            {
                $serviceGroupActivity = new CPM_ServiceGroupActivity();
                $serviceGroupActivity->activity_id = $activityId;
                $serviceGroupActivity->service_group_id = $serviceGroupId;
                $serviceGroupActivity->save();
            }

            DB::commit();
            return $cpmActivity;
        }
        catch (Exception $e)    {    DB::rollback();      throw $e;       }
    }
    public function save($request)
    {
        try {
            DB::beginTransaction();

            $cpmActivity =  $this->model->findOrFail($request->input('id'));
            $cpmActivity->name = $request->input('name');
            $cpmActivity->comment = $request->input('comment');
            $cpmActivity->duration = $request->input('duration');
            $cpmActivity->is_full = $request->input('isFull');
            $cpmActivity->sql_statement = $request->input('sql_statement');
            $cpmActivity->action_sql = $request->input('action_sql');
            $cpmActivity->tick_option = $request->input('tick_option');
            $serviceId = $request->input('service.id');
            $service = CPM_Service::where('id', $serviceId)->where('active',1)->first();
            if (! $service instanceof CPM_Service)
            {
                throw new Exception('The service is invalid!');
            }
            $cpmActivity->service_id =$service->id;
            $cpmActivity->save();

            // update CPM_ServiceGroupActivity
            $activityId = $request->input('id');
            $serviceGroupId = $request->input('service_group');
            if ($serviceGroupId)
            {
                $serviceGroupActivity = CPM_ServiceGroupActivity::where('activity_id', $activityId)->where('active',1)->first();
                if (! $serviceGroupActivity instanceof CPM_ServiceGroupActivity)
                {
                    $serviceGroupActivity = new CPM_ServiceGroupActivity();
                    $serviceGroupActivity->activity_id = $activityId;
                }
                $serviceGroupActivity->service_group_id = $serviceGroupId;
                $serviceGroupActivity->save();
            }
            else
            {
                $serviceGroupActivity = CPM_ServiceGroupActivity::where('activity_id', $activityId)->where('active',1)->first();
                if ($serviceGroupActivity instanceof CPM_ServiceGroupActivity)
                {
                    //throw new Exception('Sorry, the service group cannot be empty!');
                    // delete from the service group
                    $serviceGroupActivity->active = 0;
                    $serviceGroupActivity->save();
                }
            }
            DB::commit();
            return $cpmActivity;
        }
        catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }
    public function delete($request)
    {
        try {
            DB::beginTransaction();

            $cpmActivity =  $this->model->findOrFail($request->input('id'));
            $cpmActivity->active = false;
            $cpmActivity->save();

            $activityId = $cpmActivity->id;
            // delete from cpm_service_group_activities
            $serviceGroupActivity = CPM_ServiceGroupActivity::where('activity_id', $activityId)->where('active',1)->first();
            if ($serviceGroupActivity instanceof CPM_ServiceGroupActivity)
            {
                $serviceGroupActivity->active = false;
                $serviceGroupActivity->save();
            }

            DB::commit();
            return $cpmActivity;
        }
        catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }
    public function getCpmActivityOptions($request)
    {
        $serviceId = trim($request->input('service_id'));
        $service = CPM_Service::where('id', $serviceId)->where('active',1)->first();
        if (! $service instanceof CPM_Service)
        {
            throw new Exception('The service parameter is invalid!');
        }
        return $this->model->where('service_id', $serviceId)->where('active', 1)->get();
    }
    public function getAssociatedUserOptions($request)
    {
        // 1) get user
        // 2) get Role based on user
        $user = Auth::user();
        //$userId = $request->userId;
        //$user = User::where('active',1)->where('id', $userId)->first();
        if (!$user instanceof User)
        {
            throw new Exception('Invalid user passed in!');
        }
        $roles = $user->roles();
        $roleIds = $roles->pluck('id')->all();
        $rawSql = '
                    WITH ret AS(
                    SELECT  *
                    FROM    roles
                    WHERE active = 1 and id in (' . implode(',', $roleIds)
        .          ')
                    UNION ALL
                    SELECT  t.*
                     FROM  roles t INNER JOIN
                     ret r ON t.parent_id = r.id
                     WHERE t.active = 1
                    )
                     SELECT distinct user_group.user_id from groups
                     inner join ret
                     on groups.role_id = ret.id
                     inner join user_group on user_group.group_id = groups.id
                     order by user_group.user_id';
        $userIds = DB::select($rawSql);
        $userIds = array_column($userIds, 'user_id');
        $users = User::select([DB::raw("id AS value"), DB::raw("name AS label")])->where('active',1)
            ->wherein('id', $userIds)->get();
        return $users;
    }
    public function getAssociatedGroupOptions($request)
    {
        // 1) get user
        // 2) get Role based on user
        $user = Auth::user();
        //$userId = $request->userId;
        //$user = User::where('active',1)->where('id', $userId)->first();
        if (!$user instanceof User)
        {
            throw new Exception('Invalid user passed in!');
        }
        $roles = $user->roles();
        $roleIds = $roles->pluck('id')->all();
        $rawSql = '
                    WITH ret AS(
                    SELECT  *
                    FROM    roles
                    WHERE   active = 1 and id in (' . implode(',', $roleIds)
            .          ')
                    UNION ALL
                    SELECT  t.*
                    FROM  roles t INNER JOIN
                    ret r ON t.parent_id = r.id
                    WHERE t.active = 1
                    )
                    SELECT distinct groups.id as value, groups.name as label from groups
                     inner join ret
                     on groups.role_id = ret.id
                     order by groups.id';
        $groups = DB::select($rawSql);
        return $groups;
    }
    public function getAssociatedUsers($request)
    {
        return $this->getAssociatedUsersMangers($request, BaseModel::OWNER_TYPE_USER);
    }
    public function getAssociatedUsersMangers($request, $ownerType)
    {
        $activityId =  $request->activityId;
        $rawSql = "SELECT user_id AS id, 'USER' AS type
                FROM cpm_activity_users
                WHERE cpm_activity_id = ? AND owner_type = ? AND active = 1
                UNION
                SELECT group_id AS id, 'GROUP' AS type
                FROM cpm_activity_groups
                WHERE cpm_activity_id = ? AND owner_type = ? AND active = 1";
        //$usersOrManagers = DB::select($rawSql,  array('cpm_activity_id' => $activityId, 'owner_type' => $ownerType));
        $usersOrManagers = DB::select($rawSql,  array($activityId, $ownerType, $activityId, $ownerType));
        return $usersOrManagers;
    }
    public function getAssociatedManagers($request)
    {
        return $this->getAssociatedUsersMangers($request, BaseModel::OWNER_TYPE_MANAGER);
    }
    public function updateAssociatedUser($request)
    {
        try {
            DB::beginTransaction();
            $updatedUsers = [];
            $updatedManagers = [];
            $associatedUsers = $request->associatedUsers;
            $associatedManagers = $request->associatedManagers;
            $activityId =  $request->activityId;
            // $serviceId = $request->serviceId;
            // $locationId = $request->locationId;

            // update users
            // 1) delete all first
            // 2) add all
            $deletedUsers = CPMActivityUser::where('cpm_activity_id', $activityId)
                ->where('active', 1)->update(['active' => 0]);
            self::LogEntity(null,  'deleted users success', __CLASS__ . '::' .__FUNCTION__);
            $deletedGroups = CPMActivityGroup::where('cpm_activity_id', $activityId)
                ->where('active', 1)->update(['active' => 0]);
            self::LogEntity(null,  'deleted groups success', __CLASS__ . '::' .__FUNCTION__);

            if (count($associatedUsers) > 0)
            {
                $updatedUsers = $this->updateUsersGroups($activityId, $associatedUsers, BaseModel::OWNER_TYPE_USER);
            }
            if (count($associatedManagers) > 0)
            {
                $updatedManagers = $this->updateUsersGroups($activityId, $associatedManagers, BaseModel::OWNER_TYPE_MANAGER);
            }
            DB::commit();
            $updatedUsersGroups = array_merge($updatedUsers, $updatedManagers);
            return $updatedUsersGroups;
        }
        catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }
    public function updateUsersGroups($activityId, $associatedUsersGroups, $userType)
    {
        $updatedUsersGroups = [];
        foreach($associatedUsersGroups as $associatedUserGroup)
        {
            $ownerType = $associatedUserGroup['type'];
            // reuse old ones
            switch ($ownerType)
            {
                case BaseModel::USER_TYPE_SINGLE_USER:
                    $userId = $associatedUserGroup['id'];
                    $deletedUser = CPMActivityUser::where('cpm_activity_id', $activityId)
                        ->where('owner_type', $userType)
                        ->where('user_id', $userId)->first();
                    if ($deletedUser instanceof CPMActivityUser)
                        $updatingUser = $deletedUser;
                    else
                        $updatingUser = New CPMActivityUser();
                    $updatingUser->cpm_activity_id = $activityId;
                    $updatingUser->owner_type = $userType;
                    $updatingUser->user_id = $userId;
                    $updatingUser->active = 1;
                    $updatingUser->save();
                    $updatedUsersGroups[] = $updatingUser;
                    break;
                case BaseModel::USER_TYPE_GROUP:
                    $groupId = $associatedUserGroup['id'];
                    $deletedGroup = CPMActivityGroup::where('cpm_activity_id', $activityId)
                        ->where('owner_type', $userType)
                        ->where('group_id', $groupId)->first();
                    if ($deletedGroup instanceof CPMActivityGroup)
                        $updatingGroup = $deletedGroup;
                    else
                        $updatingGroup = New CPMActivityGroup();
                    $updatingGroup->cpm_activity_id = $activityId;
                    $updatingGroup->owner_type = $userType;
                    $updatingGroup->group_id = $groupId;
                    $updatingGroup->active = 1;
                    $updatingGroup->save();
                    $updatedUsersGroups[] = $updatingGroup;
                    break;
            }
        }
        return $updatedUsersGroups;
    }
}