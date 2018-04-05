<?php
namespace App\Models\Repositories;

use App\Models\Entities\Group;
use App\Models\Entities\Role;
use Exception;
use Illuminate\Support\Facades\Auth;
use DB;

class GroupRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\Group';
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

        $user = Auth::user();
        $roles = $user->roles();
        $roleIds = [];
        foreach($roles as $role)
        {
            $roleIds[] = $role->id;
        }

        $rawSql = '
                   WITH ret AS(
                    SELECT  *
                    FROM    roles
                    WHERE active = 1 and id in (' . implode(',', $roleIds) . ')' .
                    ' UNION ALL
                    SELECT  t.*
                     FROM  roles t INNER JOIN
                     ret r ON t.parent_id = r.id
                    )
                     SELECT groups.id from groups
                     inner join ret
                     on groups.role_id = ret.id 
                    order by groups.id';
        $group_Ids = DB::select($rawSql);
        $group_Ids = array_column($group_Ids, 'id');
        $group_Ids = array_unique($group_Ids);

        $query = $this->model->select('groups.*')
            ->join('roles', 'roles.id', '=', 'groups.role_id')
            ->orderBy($sortBy, $sortDirection)
            ->where('groups.active',1)
            ->wherein('groups.id', $group_Ids)
            ->with('role');

        if ($search) {
            $like = "%{$search}%";

            $query = $query->where(function ($query) use ($like) {
                $query->where('groups.name', 'LIKE', $like)
                    ->orWhere('roles.name','LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
    public function add($request)
    {
        $group =  new Group();
        $group->name = $request->input('name');
        $group->comment = $request->input('comment');
        $roleName = $request->input('role.name');
        $role = Role::where('name', $roleName)->where('active',1)->first();
        if (! $role instanceof Role)
        {
            throw new Exception('The role is invalid!');
        }
        $group->role_id =$role->id;
        $group->save();
        return $group;
    }
    public function save($request)
    {
        $group =  $this->model->findOrFail($request->input('id'));
        $group->name = $request->input('name');
        $group->comment = $request->input('comment');
        $roleName = $request->input('role.name');
        $role = Role::where('name', $roleName)->where('active',1)->first();
        if (! $role instanceof Role)
        {
            throw new Exception('The role is invalid!');
        }
        $group->role_id =$role->id;
        $group->save();
        return $group;
    }
    public function delete($request)
    {
        $group =  $this->model->findOrFail($request->input('id'));
        $group->active = 0;
        $group->save();
        return $group;
    }

        public function usersaspergroups()
        { 
            $users = $this->model->where('active', 1)->with('usergroups1')->get();  // get all resources
            $cascadeElements = [];  // create cascade array
            foreach($users as $state)
            {   if (count($state->usergroups1) == 0) continue;
                $obj = new \stdClass();
                $obj->value = $state->id;     $obj->label = $state->name;
                $obj->children = [];
                foreach($state->usergroups1 as $location)
                {   $child = new \stdClass();
                    $child->value = $location->user->id;
                   // $child->label = $location->name;
                    $child->label = $location->user->name;
                    $child->children = [];
                    $obj->children[] = $child;
                }
                $cascadeElements[] = $obj;
            }
            return $cascadeElements;
        }
   
}