<?php

namespace App\Models\Services;

use App\Models\Entities\Role;
use App\Models\Entities\User;
use App\Models\Entities\Permission;
use App\Models\Repositories\RoleRepository;
use App\Models\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserService extends BaseService
{
    protected $userRepository;
    protected $roleRepository;

    public function __construct(UserRepository $userRepository,
                                RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }
    public function getRoles($user)
    {
        return $this->userRepository->getRoles($user);
    }
    /**
     * @param User $user
     * @param $resourceType
     * @return array
     */
    public function getAclResourceByType(User $user, $resourceType)
    {   $resRets = [];   $groups = $user->groups();
        foreach($groups as $group)
        {   $resources= $group->groupResourcePermissionsByType($resourceType);
            foreach($resources as $resource)
            {   if (!$resource->resource) continue;
                $newObj = $resource->resource;     $newObj->permission = $resource->permission->name;
                if (isset($resRets[$newObj->id]))
                {   $existingRes = $resRets[$newObj->id];
                    $existingRes['permission'] = Permission::getHigherPermission($existingRes['permission'],
                    $newObj->permission);
                }
                else   $resRets[$newObj->id] = $newObj->toArray();
            }
        }
        return $resRets;
    }
    public static function getAclResource(User $user, $resourceType)
    {
        $resRets = [];
        $groups = $user->groups();

        foreach($groups as $group)
        {
            $resources= $group->groupResourcePermissionsByType($resourceType);
            foreach($resources as $resource)
            {
                if (!$resource->resource) continue;
                $newObj = $resource->resource;
                $newObj->permission = $resource->permission->name;
                if (isset($resRets[$newObj->id]))
                {
                    $existingRes = $resRets[$newObj->id];
                    $existingRes['permission'] = Permission::getHigherPermission($existingRes['permission'],
                        $newObj->permission);
                }
                else
                    $resRets[$newObj->id] = $newObj->toArray();
            }
        }
        return $resRets;
    }
    public function getAllUserNodes()
    {
        return $this->userRepository->getAllUserNodes();
    }
    public function update($request)
    {
        return $this->userRepository->save($request);
    }
    public function add($request)
    {
        return $this->userRepository->add($request);
    }
    public function delete($request)
    {
        return $this->userRepository->delete($request);
    }
    public function getByPaginate(Request $request)
    {
        return $this->userRepository->getByPaginate($this->roleRepository, $request);
    }
//    public function getRoleOptions(Request $request)
//    {
//        $selectedRoleName = $request->selectedRole;
//        $selectedRole = Role::where('name', $selectedRoleName)->first();
//        $roleOptions = [];
//        $roleOptions = $this->roleRepository->getAllChildren($selectedRole, $roleOptions, $selectedRole->id);
//        return $roleOptions;
//    }
    public function getRoleOptions(Request $request)
    {
        $selectedRoleName = $request->selectedRole;
        $selectedRole = Role::where('name', $selectedRoleName)->first();
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
                 SELECT  *
                 FROM  ret order by id asc';
        $rets = DB::select($rawSql, array($selectedRole->id));
        $roleOptions = json_decode(json_encode($rets), true);
        return $roleOptions;
    }
    public function getGroupOptions(Request $request)
    {
        $selectedRoleName = $request->selectedRole;
        $selectedRole = Role::where('name', $selectedRoleName)->first();
        $groups = $selectedRole->groups;
        return $groups;
    }
    public function getCascadeRolesGroups($request)
    {
        $selectedRoleName = $request->selectedRole;
        $selectedRole = Role::where('name', $selectedRoleName)->first();
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
                 SELECT  id
                 FROM  ret order by id asc';
        $rets = DB::select($rawSql, array($selectedRole->id));
        $roleIds = array_column($rets, 'id');

        $roles = Role::where('active',1)
                            ->wherein('id', $roleIds)
                            ->with('groups')
                            ->get();
        // create cascade array
        $cascadeElements = [];
        foreach($roles as $role)
        {
            $obj = new \stdClass();
            $obj->value = $role->id;
            $obj->label = $role->name;
            $obj->children = [];
            foreach($role->groups as $group)
            {
                $child = new \stdClass();
                $child->value = $group->id;
                $child->label = $group->name;
                $child->children = [];
                $obj->children[] = $child;
            }
            $cascadeElements[] = $obj;
        }
        return $cascadeElements;
    }
}