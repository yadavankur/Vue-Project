<?php

namespace App\Models\Services;

use App\Models\Entities\Tab;
use App\Models\Repositories\TabRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TabService extends BaseService
{
    protected $tabRepository;

    public function __construct(TabRepository $tabRepository)
    {
        $this->tabRepository = $tabRepository;
    }

    public function getAll($user)
    {
        return $this->tabRepository->getAll($user);
    }
    public function getByPaginate(Request $request)
    {
        return $this->tabRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->tabRepository->save($request);
    }
    public function add($request)
    {
        return $this->tabRepository->add($request);
    }
    public function delete($request)
    {
        return $this->tabRepository->delete($request);
    }
    public function getPermission($tabLink, $group_Ids)
    {
        $where = array('link' => $tabLink, 'active' => 1);
        $tab = $this->tabRepository->findWhere($where)->first();
        if (!$tab instanceof Tab) {
            return 1;
        }
        $rawSql = 'select min(grp.permission_id) AS permissionId
              FROM tabs 
              inner join group_resource_permission AS grp
              on tabs.id = grp.resource_id
              WHERE grp.active = 1 and tabs.active = 1
              and grp.resource_type_id = 3
              and tabs.id = ?
              and grp.group_id in ('  . implode(',', $group_Ids) . ')';
        $rets = DB::select($rawSql, array($tab->id));
        $permission = json_decode(json_encode($rets));
        return $permission;
    }
}