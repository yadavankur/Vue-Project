<?php

namespace App\Models\Repositories;

use App\Models\Entities\Group;
use App\Models\Entities\GroupResourcePermission;
use App\Models\Entities\Permission;
use App\Models\Entities\ResourceType;
use Exception;
use App\Models\Entities\Role;
use DB;


class GroupResourcePermissionRepository extends BaseRepository
{
    public function model() {
        return 'App\Models\Entities\GroupResourcePermission';
    }
    public function add($request)
    {
        try {
            DB::beginTransaction();

            $groupName = $request->input('group');
            $group = Group::where('name', $groupName)->where('active', 1)->first();

            DB::commit();
            return $group;
        }
        catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }
    public function save($request)
    {
        try {
            DB::beginTransaction();
            $group = Group::where('id', $request->input('id'))->where('active', 1)->first();
            $groupName = $request->input('name');
            $roleName = $request->input('role');
            // update group_resource_permission table
            // first delete all records belongs to this group in group_resource_permission table
            // then insert new ones
            $grps = $request->input('group_resource_permissions');
            if (count($grps) == 0)
            {
                // need check?
                // throw new Exception('No resources are assigned to this group!');
            }
            $deletedGrps = GroupResourcePermission::where('group_id', $group->id)
                ->where('active', 1)->update(['active' => 0]);
            foreach($grps as $grp)
            {
                $resourceTypeName = $grp['resourceType']['name'];
                $resourceType = ResourceType::where('name', $resourceTypeName)
                                ->where('active',1)->first();
                $resourceClass = 'App\\Models\\Entities\\' . $resourceType->name;

                $resourceId = $grp['resource']['id'];
                $resource = $resourceClass::where('id', $resourceId)
                    ->where('active',1)->first();
//                if ($resourceTypeName == 'Location')
//                {
//                    $resource = $resourceClass::where('id', $resourceId)
//                        ->where('active',1)->first();
//                }
//                else if ($resourceTypeName == 'Menu')
//                {
//                    // only one format of 'Menu'
//                    $resource = $resourceClass::where('id', $resourceId)
//                        ->where('active',1)->first();
//                }
//                else if ($resourceTypeName == 'Tab')
//                {
//                    $resource = $resourceClass::where('id', $resourceId)
//                        ->where('active',1)->first();
//                }
//                else if ($resourceTypeName == 'Page')
//                {
//                    // only one format of 'Page'
//                    $resource = $resourceClass::where('id', $resourceId)
//                        ->where('active',1)->first();
//                }
//                else if ($resourceTypeName == 'Component')
//                {
//                    $resource = $resourceClass::where('id', $resourceId)
//                        ->where('active',1)->first();
//                }
//                else if ($resourceTypeName == 'Process')
//                {
//                    $resource = $resourceClass::where('id', $resourceId)
//                        ->where('active',1)->first();
//                }
                $permission = Permission::where('name', $grp['permission']['name'])
                                ->where('active',1)->first();
                // reuse old ones
                $deletedGrp = GroupResourcePermission::where('group_id', $group->id)
                    ->where('resource_type_id', $resourceType->id)
                    ->where('permission_id', $permission->id)
                    ->where('resource_id', $resource->id)->first();
                if ($deletedGrp instanceof GroupResourcePermission)
                    $updateGrp = $deletedGrp;
                else
                    $updateGrp = New GroupResourcePermission();
                $updateGrp->resource_type_id = $resourceType->id;
                $updateGrp->resource_id = $resource->id;
                $updateGrp->group_id = $group->id;
                $updateGrp->permission_id = $permission->id;
                $updateGrp->active = 1;
                $updateGrp->save();

            }
            DB::commit();
            return $group;
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
            $group = Group::where('id', $request->input('id'))->where('active', 1)->first();
            $deletedGrps = GroupResourcePermission::where('group_id', $group->id)
                ->where('active', 1)->update(['active' => 0]);
            DB::commit();
            return $group;
        }
        catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }
//    public function getByPaginate(RoleRepository $roleRepository,
//                                  GroupRepository $groupRepository,
//                                  $request)
//    {
//
//        $selectedRole = $request->selectedRole;
//
//        $selectedRole = Role::where('name', $selectedRole)->first();
//
//        $roleIds = $roleRepository->getAllChildrenIds($selectedRole);
//
//        $group_Ids = [];
//
//        foreach($roleIds as $roleId)
//        {
//            $retGroups = $this->getGroupsOfRole($roleId);
//            foreach($retGroups as $retGroup)
//            {
//                $group_Ids[] = $retGroup;
//            }
//        }
//        $group_Ids = array_unique($group_Ids);
//        $sort = $request->sort;
//        $sort = explode('|', $sort);
//
//        $sortBy = $sort[0];
//        $sortDirection = $sort[1];
//
//        $perPage = $request->per_page;
//
//        $search = $request->filter;
//
//        $query = $groupRepository->model->orderBy($sortBy, $sortDirection)
//            ->where('active',1)
//            ->wherein('id', $group_Ids)
//            ->with('groupResourcePermissions')
//            ->with('role');
//
//        if ($search) {
//            $like = "%{$search}%";
//
//            $query = $query->where(function ($query) use ($like) {
//                $query->where('name', 'LIKE', $like);
//            });
//        }
//
//        return $query->paginate($perPage);
//    }
    public function getByPaginate(RoleRepository $roleRepository,
                                  GroupRepository $groupRepository,
                                  $request)
    {

        $selectedRole = $request->selectedRole;
        $selectedRole = Role::where('name', $selectedRole)->first();
        $rawSql = '
                   WITH ret AS(
                    SELECT  *
                    FROM    roles
                    WHERE   id = ?
                    UNION ALL
                    SELECT  t.*
                     FROM  roles t INNER JOIN
                     ret r ON t.parent_id = r.id
                    )
                     SELECT groups.id from groups
                     inner join ret
                     on groups.role_id = ret.id 
                     where ret.id <> ?
                     order by groups.id';
        $group_Ids = DB::select($rawSql, array($selectedRole->id, $selectedRole->id));
        $group_Ids = array_column($group_Ids, 'id');
        $group_Ids = array_unique($group_Ids);
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;

        $search = $request->filter;

        $query = $groupRepository->model
            ->select('groups.*')
            ->join('roles', 'groups.role_id', '=', 'roles.id')
            ->orderBy($sortBy, $sortDirection)
            ->where('groups.active',1)
            ->wherein('groups.id', $group_Ids)
            ->with('groupResourcePermissions')
            ->with('role');
        if ($search) {
            $like = "%{$search}%";

            $query = $query->where(function ($query) use ($like) {
                $query->where('name', 'LIKE', $like);
            });
        }

        return $query->paginate($perPage);
    }
    /** get associated groups belong to the role
     * @param $roleId
     * @return array
     */
    public function getGroupsOfRole($roleId)
    {
        $groups = DB::table('groups')
            ->where('role_id', $roleId)->where('active', 1)
            ->get()->toArray();
        $groupIds = [];
        foreach($groups as $group)
        {
            $groupIds[] = $group->id;
        }
        return $groupIds;
    }
}