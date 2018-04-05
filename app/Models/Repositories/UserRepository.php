<?php

namespace App\Models\Repositories;

use App\Models\Entities\Group;
use App\Models\Entities\User;
use App\Models\Entities\UserGroup;
use Exception;
use App\Models\Entities\Role;
use DB;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserRepository extends BaseRepository
{
    public function model() {   return 'App\Models\Entities\User';    }
    /**
     * @param $user
     * @return mixed
     */
    public function getRoles($user)
    {
        return $user->roles();
    }
    public function getAllUserNodes()
    {
        // return $this->model->where('active',1)->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
//        $users = DB::table('user_group')->join('groups', 'groups.id', '=', 'user_group.group_id')
//            ->join('roles', 'roles.id', '=', 'groups.role_id')
//            ->where('roles.id', $role->id)
//            ->select('user_group.user_id')->get();
        return $this->model->where('active',1)->with('usergroups')
            ->get()->keyBy('id')->toArray();
        return $users;
    }
    
    public function add($request)
    {
        try {
            DB::beginTransaction();
            $user =  new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $password = $request->input('password');
            $confirmPassword = $request->input('confirmPassword');
            if ($password != $confirmPassword)
            {
                throw new Exception('Passwords do not match!');
            }
            if ($password)
            {
                $user->password = bcrypt($password);
            }
            $user->save();
            $role_groups = $request->input('rolegroups');
            if (count($role_groups) == 0)
            {
                throw new Exception('At lease one role and group must be assigned to this user!');
            }
            foreach($role_groups as $role_group)
            {

                $role = Role::where('name', $role_group['role']['name'])->first();
                $group = Group::where('name', $role_group['group']['name'])
                    ->where('role_id', $role->id)->first();
                // check whether already exist
                $existedOne = UserGroup::where('user_id', $user->id)
                    ->where('group_id', $group->id)
                    ->where('active', 1)
                    ->first();
                if ($existedOne instanceof UserGroup)
                {
                    // give error msg or just ignore?
                    continue;
                    //throw new Exception('Duplicated role/group detected, please re-confirm!');
                }
                $newRoleGroup = New UserGroup();
                $newRoleGroup->user_id = $user->id;
                $newRoleGroup->group_id = $group->id;
                $newRoleGroup->save();
            }
            DB::commit();
            return $user;
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
            $user =  $this->model->findOrFail($request->input('id'));
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $password = $request->input('password');
            $confirmPassword = $request->input('confirmPassword');
            if ($password != $confirmPassword)
            {
                throw new Exception('Passwords do not match!');
            }
            if ($password)
            {
                $user->password = bcrypt($password);
            }
            // update user_group table
            // first delete all records belongs to this user in user_group table
            // then insert new ones
            $role_groups = $request->input('rolegroups');
            if (count($role_groups) == 0)
            {
                throw new Exception('At lease one role and group must be assigned to this user!');
            }
            $user_group = UserGroup::where('user_id', $user->id)
                ->where('active', 1)->update(['active' => 0]);
            foreach($role_groups as $role_group)
            {

                $role = Role::where('name', $role_group['role']['name'])->first();
                $group = Group::where('name', $role_group['group']['name'])
                    ->where('role_id', $role->id)->first();
                // check whether already exist
                $existedOne = UserGroup::where('user_id', $user->id)
                    ->where('group_id', $group->id)
                    ->where('active', 1)
                    ->first();
                if ($existedOne instanceof UserGroup)
                {
                    // give error msg or just ignore?
                    continue;
                    //throw new Exception('Duplicated role/group detected, please re-confirm!');
                }
                // reuse old ones
                $deletedUserGroup = UserGroup::where('user_id', $user->id)
                    ->where('group_id', $group->id)
                    ->where('active', 0)
                    ->first();
                if ($deletedUserGroup instanceof UserGroup)
                    $newRoleGroup = $deletedUserGroup;
                else
                    $newRoleGroup = New UserGroup();
                $newRoleGroup->user_id = $user->id;
                $newRoleGroup->group_id = $group->id;
                $newRoleGroup->active = 1;
                $newRoleGroup->save();

            }
            $user->save();
            DB::commit();
            return $user;
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
            $user =  $this->model->findOrFail($request->input('id'));

            $currentUser = JWTAuth::parseToken()->authenticate();
            if ($currentUser->id == $user->id)
            {
                throw new Exception('Sorry, you do not have the privilege to delete yourself!');
            }
            $user->active = 0;
            $user->save();
            $user_group = UserGroup::where('user_id', $user->id)
                ->where('active', 1)->update(['active' => 0]);
            DB::commit();
            return $user;
        }
        catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }
    public function getByPaginate(RoleRepository $roleRepository, $request)
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
                     SELECT user_group.user_id from groups
                     inner join ret
                     on groups.role_id = ret.id
                     inner join user_group on user_group.group_id = groups.id
                     order by user_group.user_id';
        $userIds = DB::select($rawSql, array($selectedRole->id));
        $userIds = array_column($userIds, 'user_id');
        $userIds = array_unique($userIds);
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;

        $search = $request->filter;

        $query = $this->model->orderBy($sortBy, $sortDirection)
            ->where('active',1)
            ->wherein('id', $userIds)
            ->with('usergroups');

        if ($search) {
            $like = "%{$search}%";

            $query = $query->where(function ($query) use ($like) {
                $query->where('name', 'LIKE', $like)
                    ->orWhere('email', 'LIKE', $like);
            });
        }

        return $query->paginate($perPage);
    }
//    public function getByPaginate(RoleRepository $roleRepository, $request)
//    {
//
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
//        $query = $this->model->orderBy($sortBy, $sortDirection)
//                    ->where('active',1)
//                    ->with('usergroups');
//
//        if ($search) {
//            $like = "%{$search}%";
//
//            $query = $query->where(function ($query) use ($like) {
//                            $query->where('name', 'LIKE', $like)
//                                ->orWhere('email', 'LIKE', $like);
//                    });
//        }
//
//        return $query->paginate($perPage);
//    }
    /**
     * get associated users belong to the role
     * @param $role
     * @return mixed
     */
    public function getUsersOfRole($roleId)
    {
        $users = DB::table('user_group')->join('groups', 'groups.id', '=', 'user_group.group_id')
            ->join('roles', 'roles.id', '=', 'groups.role_id')
            ->where('roles.id', $roleId)->distinct()
            ->select('user_group.user_id as id')->get()->toArray();
        $userIds = [];
        foreach($users as $user)
        {
            $userIds[] = $user->id;
        }
        return $userIds;
    }

}