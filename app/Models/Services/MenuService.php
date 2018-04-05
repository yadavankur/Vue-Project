<?php

namespace App\Models\Services;

use App\Models\Entities\Menu;
use App\Models\Repositories\CMenuRepository;
use App\Models\Repositories\MenuRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuService extends BaseService
{
    protected $menuRepository;
    protected $cMenuRepository;

    public function __construct(MenuRepository $menuRepository, CMenuRepository $cMenuRepository)
    {
        $this->menuRepository = $menuRepository;
        $this->cMenuRepository = $cMenuRepository;
    }
    public function getAllMenuNodes()
    {
        return $this->cMenuRepository->getAllMenuNodes();
    }
    public function getAll($user)
    {
        return $this->cMenuRepository->getAll($user);
    }
    /**
     * @param $flat
     * @param $pidKey
     * @param null $idKey
     * @return mixed
     */
    public function getTreeMenus($flat, $pidKey, $idKey = null)
    {
        $grouped = array();
        foreach ($flat as $sub){
            $grouped[$sub[$pidKey]][] = $sub;
        }

        $fnBuilder = function($siblings) use (&$fnBuilder, $grouped, $idKey) {
            foreach ($siblings as $k => $sibling) {
                $id = $sibling[$idKey];
                if(isset($grouped[$id])) {
                    $sibling['children'] = $fnBuilder($grouped[$id]);
                }
                $siblings[$k] = $sibling;
            }

            return $siblings;
        };

        $tree = $fnBuilder($grouped[0]);

        return $tree;
    }
    public function update($request)
    {
        return $this->menuRepository->save($request);
    }
    public function add($request)
    {
        return $this->menuRepository->add($request);
    }
    public function delete($request)
    {
        return $this->menuRepository->delete($request);
    }
    public function getByPaginate(Request $request)
    {
        return $this->cMenuRepository->getByPaginate($request);
    }
    public function getPermission($menuLink, $group_Ids)
    {
        $where = array('link' => $menuLink, 'active' => 1);
        $menu = $this->menuRepository->findWhere($where)->first();
        if (!$menu instanceof Menu) {
            return 1;
        }
        $rawSql = 'WITH C AS
                    (
                      SELECT T.id, 
                             T.name, 
                             T.parent_id
                      FROM menus AS T
                      WHERE T.id = ? and T.active = 1
                      UNION ALL
                      SELECT T.id, 
                             T.name, 
                             T.parent_id
                      FROM menus AS T
                        INNER JOIN C
                          ON C.parent_id = T.id where T.active = 1
                    )
                    SELECT min(grp.permission_id) AS permissionId
                    FROM C
                    inner join group_resource_permission AS grp
                    on c.id = grp.resource_id
                    WHERE grp.active = 1 and grp.resource_type_id = 2 
                    and c.parent_id <> 0
                    and grp.group_id in (' . implode(',', $group_Ids) . ')';
        $rets = DB::select($rawSql, array($menu->id));
        $permission = json_decode(json_encode($rets));
        return $permission;
    }
}