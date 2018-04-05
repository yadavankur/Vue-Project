<?php

namespace App\Models\Repositories;

use App\Models\Entities\Page;
use App\Models\Entities\tab1;
use DB;

class Tab1Repository extends BaseRepository
{ 
    public function model()   {  return 'App\Models\Entities\tab1';   }
    public function getAll($user)
    {   $isAdmin = self::isAdmin($user);
        if ($isAdmin)
          {   return $this->model->where('active',1)->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
          }
        else
          { /*  $tabs = $this->model->where('active',1)->get(['*', DB::raw("'H' as permission")])->keyBy('id')->toArray();
            // dashboard is permitted for every one
            $dashboardTabs = array_filter($tabs, function($tab) { return $tab['name'] == Tab::TAB_DASHBOARD_NAME;  });
            foreach ($dashboardTabs as $dashboardTab)
                {   $dashboardTab['permission'] = 'RW'; $tabs[$dashboardTab['id']] = $dashboardTab;   }
            return $tabs;
            */
          }
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
            ->where('active',1)->with('page');

        if ($search) 
        {   $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) {  $query->where('name', 'LIKE', $like);  });
        }
        return $query->paginate($perPage);
    }
}