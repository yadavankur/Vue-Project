<?php

namespace App\Models\Repositories;

use App\Models\Entities\Role;
use DB;
use Illuminate\Support\Facades\Auth;

class RoleRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\Role';
    }
//    public function getAll($pageId)
//    {
//        return $this->model->where('active', 1)->where('page_id', $pageId)
//            ->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
//    }
//    /**
//     * @param $flat
//     * @param $pidKey
//     * @param null $idKey
//     * @return mixed
//     */
//    static public function getTreeRoles($flat, $pidKey, $idKey = null)
//    {
//        $grouped = array();
//        $first = $flat[key($flat)];
//        $rootId = $first['parent_id'];
//        $level = 0;
//        foreach ($flat as $sub) {
//            $grouped[$sub[$pidKey]][] = $sub;
//        }
//        $fnBuilder = function($siblings) use (&$fnBuilder, $grouped, $idKey, &$level) {
//            $level++;
//            foreach ($siblings as $k => $sibling) {
//                $id = $sibling[$idKey];
//                if(isset($grouped[$id])) {
//                    $sibling['children'] = $fnBuilder($grouped[$id]);
//                    $level--;
//                }
//                $siblings[$k] = $sibling;
//                $siblings[$k]['level'] = $level;
//            }
//            return $siblings;
//        };
//        $tree = $fnBuilder($grouped[$rootId]);
//
//        return $tree;
//    }
//
//    static public function getChildrenRoles($ary, $role)
//    {
//        $results = array();
//
//        foreach($ary as $el)
//        {
//            if ($el['parent_id'] == $role->id)
//            {
//                $results[] = $el;
//            }
//            if (count($el['children']) > 0 && ($children = getChildrenRoles($el['children'], $role)) !== FALSE)
//            {
//                $results = array_merge($results, $children);
//            }
//        }
//
//        return count($results) > 0 ? $results : array();
//    }
    public function add($request)
    {
        $role =  new Role();
        $role->parent_id = $request->input('parent_id');
        $role->name = $request->input('name');
        $role->comment = $request->input('comment');
        $canEdit = $request->input('can_edit');
        $role->can_edit = $canEdit;
        $isAdmin =  $request->input('is_admin');
        $role->is_admin = $isAdmin;
        $isRoot =  $request->input('is_root');
        $role->is_root = $isRoot;
        $role->save();
        return $role;
    }
    public function save($request)
    {
        $role =  $this->model->findOrFail($request->input('id'));
        $role->name = $request->input('name');
        $role->comment = $request->input('comment');
        $canEdit = $request->input('can_edit');
        $role->can_edit = $canEdit;
        $isAdmin =  $request->input('is_admin');
        $role->is_admin = $isAdmin;
        $isRoot =  $request->input('is_root');
        $role->is_root = $isRoot;
        $role->save();
        return $role;
    }
    public function delete($request)
    {
        $role =  $this->model->findOrFail($request->input('id'));

        // 1) check whether this role has children if yes cannot be deleted
        $childrenRole = $this->findAllBy('parent_id', $role->id);
        if (count($childrenRole) > 0)
        {
            throw new \Exception('You Cannot delete this role because it has children roles.');
        }
        // 2) check whether there are users assigned to this role if yes cannot be deleted
        $users = $this->getUsersOfRole($role);
        if (count($users) > 0)
        {
            throw new \Exception('You Cannot delete this role because it has relevant users.');
        }
        $role->active = 0;
        $role->save();
        return $role;
    }
    /**
     * @param $user
     * @return array
     */
    public function getChildRoles($user)
    {
        $roles = $user->roles();
        $treeRoles = [];
        foreach($roles as $role)
        {
            $retRoles = [];
            $retRoles = $this->getAllChildren($role, $retRoles, $role->id);
            $treeRoles[$role->name] = $retRoles;
        }
        return $treeRoles;
    }

    /**
     * @param $role
     * @param null $result
     * @param int $starting
     * @return array|null
     */
    public function getAllChildren($role, &$result = null, $starting = 0)
    {
        if($starting === 0) // initiate recursive function
        {
            $starting = $role->id;
            $result = array();
        }
        else $result[$role->id] = $this->model->with('parent')->where('active', 1)->find($role->id)->toArray();

        $children = $this->model->where('parent_id', $role->id)->where('active', 1)->get();
        foreach ($children as $child)
        {
            $this->getAllChildren($child, $result, $child->id);
        }
        return $result;
    }

    /**
     * get associated users belong to the role
     * @param $role
     * @return mixed
     */
    public function getUsersOfRole($role)
    {
        $users = DB::table('user_group')->join('groups', 'groups.id', '=', 'user_group.group_id')
            ->join('roles', 'roles.id', '=', 'groups.role_id')
            ->where('roles.id', $role->id)
            ->select('user_group.user_id')->get();
        return $users;
    }
    public function getGroupsOfRole($role)
    {

    }
    public function getAllChildrenIds($role)
    {
        $roleIDs = [];
        $roles = $this->getAllChildren($role, $roles, $role->id);

        foreach($roles as $role)
        {
            $roleIDs[] = $role['id'];
        }
        return $roleIDs;
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;

        $search = $request->filter;

        $query = $this->model->orderBy($sortBy, $sortDirection)
            ->where('active',1);

        if ($search) {
            $like = "%{$search}%";

            $query = $query->where(function ($query) use ($like) {
                $query->where('name', 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
    public function getAllRoleOptions($request)
    {
        $user = Auth::user();
        $roles = $user->roles();
        $roleIDs = [];
        foreach($roles as $role)
        {
            $roleIDs[] = $role['id'];
        }

        $rawSql = '
                  WITH ret AS(
                   SELECT  *
                   FROM    roles
                   WHERE   active = 1 and id in (' . implode(',', $roleIDs) . ')' .
                   ' UNION ALL
                   SELECT  t.*
                   FROM  roles t 
                   INNER JOIN
                         ret r ON t.parent_id = r.id
                 )
                 SELECT  *
                 FROM  ret order by id asc';
        $rets = DB::select($rawSql);
        $roleOptions = json_decode(json_encode($rets), true);
        return $roleOptions;
    }
}