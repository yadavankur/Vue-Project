<?php

namespace App\Models\Repositories;

use App\Models\Entities\CMenu;
use DB;
class CMenuRepository extends BaseRepository
{
    const MENU_ID_ROOT = 1;
    public function model()
    {
        return 'App\Models\Entities\CMenu';
    }
    public function getAll($user)
    {
//        $isAdmin = false;
//        $adminUserIds = [1, 2, 3]; // for the time being
//        $roles = $user->roles();
//        foreach($roles as $role)
//        {
//            if (in_array($role->id, $adminUserIds))
//            {
//                $isAdmin = true;
//                break;
//            }
//        }
        //$isAdmin = self::isAdmin($user);
        $isRoot = self::isRoot($user);
        if ($isRoot)
            return $this->model->where('active',1)->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
        else
        {
            $menus = $this->model->where('active',1)->get(['*', DB::raw("'H' as permission")])->keyBy('id')->toArray();
            // dashboard is permitted for every one
            $dashboardMenus = array_filter($menus, function($menu) {
                return $menu['name'] == CMenu::MENU_HOME;
            });
            foreach ($dashboardMenus as $dashboardMenu)
            {
                $dashboardMenu['permission'] = 'RW';
                $menus[$dashboardMenu['id']] = $dashboardMenu;
            }
            return $menus;
        }
    }
    public function getAllMenuNodes()
    {
        // get menu root
        $rootMenu = $this->find(self::MENU_ID_ROOT);
        $menuNods = [];
        $menuNods = $this->getAllChildren($rootMenu, $menuNods, $rootMenu->id);
        return $menuNods;

    }
    /**
     * @param $menu
     * @param null $result
     * @param int $starting
     * @return array|null
     */
    public function getAllChildren($menu, &$result = null, $starting = 0)
    {
        static $level = 1;
        if($starting === 0) // initiate recursive function
        {
            $starting = $menu->id;
            $result = array();
        }
        else
        {
            $result[$menu->id] = $this->model->with('parent')->where('active', 1)->find($menu->id)->toArray();
            $result[$menu->id]['level'] = $level++;
        }

        $children = $this->model->where('parent_id', $menu->id)->where('active', 1)->get();
        foreach ($children as $child)
        {
            $this->getAllChildren($child, $result, $child->id);
        }
        $level--;
        return $result;
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;

        $search = $request->filter;

//        $query = $this->model->orderBy($sortBy, $sortDirection)
//            ->where('active',1)->with('parent');
//        $query = $this->model->select('menus.*')
//            ->Join(DB::raw('menus as pmenus'), 'menus.parent_id' , '=', DB::raw('pmenus.id'), 'left outer')
//            ->orderBy($sortBy, $sortDirection)
//            ->where('menus.active',1)
//            ->with('parent');
        $strSql = 'WITH cmenus
                    AS
                    (
                    SELECT
                            tbl.*,
                            1 AS level
                        FROM
                            menus as tbl
                        WHERE
                            tbl.parent_id = 0
                        UNION ALL
                        SELECT
                            tbl.*,
                            cmenus.level+1 AS level
                        FROM
                            menus AS tbl
                            JOIN cmenus
                                ON tbl.parent_id = cmenus.id
                    )
                    SELECT  *  FROM cmenus
                   ';
//        $perPage = $request->input("per_page", 10);
//        $page = $request->input("page", 1);
//        $skip = $page * $perPage;
//        if($take < 1) { $take = 1; }
//        if($skip < 0) { $skip = 0; }
//
//        $basicQuery = DB::select(DB::raw(" select a.user_id, a.user_email, a.user_account_status, a.created_at, b.s_account_limit AS account_limit, c.consumed_limit, ((b.s_account_limit*1024) - c.consumed_limit) AS remaining_limit FROM upload_limits as b, users AS a JOIN user_upload_limits as c WHERE (a.user_id=c.user_id) AND a.user_type='Simple'"));
//        $totalCount = $basicQuery->count();
//        $results = $basicQuery
//            ->take($perPage)
//            ->skip($skip)
//            ->get();
//
//        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($results, $totalCount, $take, $page);

//        return $paginator;

        $query = $this->model->select('cmenus.*')
            ->join(DB::raw('menus as pmenus'), 'cmenus.parent_id' , '=', DB::raw('pmenus.id'), 'left outer')
            ->orderBy($sortBy, $sortDirection)
            ->where('cmenus.active',1)
            ->with('parent');
        if ($search) {
            $like = "%{$search}%";

            $query = $query->where(function ($query) use ($like) {
                $query->where('cmenus.name', 'LIKE', $like)
                    ->orwhere(DB::raw('pmenus.name'), 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
    public function getCascadeMenus()
    {
        $cmenus = $this->model->where('active', 1)->where('parent_id', 1)->with('children')->get();
        // create cascade array
        $cascadeElements = [];
        foreach($cmenus as $cmenu)
        {
            $obj = new \stdClass();
            $obj->value = $cmenu->id;
            $obj->label = $cmenu->name;
            $obj->children = [];
            foreach($cmenu->children as $childMenu)
            {
                $child = new \stdClass();
                $child->value = $childMenu->id;
                $child->label = $childMenu->name;
                $child->children = [];
                $obj->children[] = $child;
            }
            $cascadeElements[] = $obj;
        }
        return $cascadeElements;
    }
}
