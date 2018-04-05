<?php

namespace App\Models\Repositories;

use App\Models\Entities\Page;
use App\Models\Entities\Tab;
use DB;

class TabRepository extends BaseRepository
{    public function model()   {  return 'App\Models\Entities\Tab';   }
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
    public function add($request)
    {
        $tab =  new Tab();
        $tab->name = $request->input('name');
        $tab->link = $request->input('link');
        $tab->comment = $request->input('comment');
        $tab->isGShow = $request->input('isGShow');
        $pageName = $request->input('page.name');
        $page = Page::where('name', $pageName)->where('active',1)->first();
        $tab->page_id =$page? $page->id: 0;
        $tab->save();
        return $tab;
    }
    public function save($request)
    {
        $tab =  $this->model->findOrFail($request->input('id'));
        $tab->name = $request->input('name');
        $tab->link = $request->input('link');
        $tab->comment = $request->input('comment');
        $tab->isGShow = $request->input('isGShow');
        $pageName = $request->input('page.name');
        $page = Page::where('name', $pageName)->where('active',1)->first();
        $tab->page_id =$page? $page->id: 0;
        $tab->save();
        return $tab;
    }
    public function delete($request)
    {
        $tab =  $this->model->findOrFail($request->input('id'));
        $tab->active = 0;
        $tab->save();
        return $tab;
    }

    /**
     * @return array
     */
    public function getCascadeGlobalTabs()
    {
        $tabs = $this->model->where('active', 1)->where('page_id', 0)->get();
        // create cascade array
        $cascadeElements = [];
        foreach($tabs as $tab)
        {   $obj = new \stdClass();
            $obj->value = $tab->id;
            $obj->label = $tab->name;
            $obj->children = [];
            $cascadeElements[] = $obj;
        }
        return $cascadeElements;
    }
}