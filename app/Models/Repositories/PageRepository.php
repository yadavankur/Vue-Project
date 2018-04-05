<?php

namespace App\Models\Repositories;

use App\Models\Entities\Page;

class PageRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\Page';
    }

    public function getPageByName($pageName)
    {
        return $this->model->where('name', $pageName)->where('active', 1)->first();
    }
    public function getAll($user)
    {
//        $isAdmin = false;
//        $adminUserIds = [1, 2, 3];
//        $roles = $user->roles();
//        foreach($roles as $role)
//        {
//            if (in_array($role->id, $adminUserIds))
//            {
//                $isAdmin = true;
//                break;
//            }
//        }
        $isAdmin = self::isAdmin($user);
        if ($isAdmin)
            return $this->model->where('active',1)->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
        else
            return $this->model->where('active',1)->get(['*', DB::raw("'H' as permission")])->keyBy('id')->toArray();

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
    public function add($request)
    {
        $page =  new Page();
        $page->name = $request->input('name');
        $page->comment = $request->input('comment');
        $page->save();
        return $page;
    }
    public function save($request)
    {
        $page =  $this->model->findOrFail($request->input('id'));
        $page->name = $request->input('name');
        $page->comment = $request->input('comment');
        $page->save();
        return $page;
    }
    public function delete($request)
    {
        $page =  $this->model->findOrFail($request->input('id'));
        $page->active = 0;
        $page->save();
        return $page;
    }
    public function getCascadeTabs()
    {
        $pagesWithTabs = $this->model->where('active', 1)->with('tabs')->get();
        // create cascade array
        $cascadeElements = [];
        foreach($pagesWithTabs as $pageWithTabs)
        {
            if (count($pageWithTabs->tabs) == 0) continue;
            $obj = new \stdClass();
            $obj->value = $pageWithTabs->id;
            $obj->label = $pageWithTabs->name;
            $obj->children = [];
            foreach($pageWithTabs->tabs as $tab)
            {
                $child = new \stdClass();
                $child->value = $tab->id;
                $child->label = $tab->name;
                $child->children = [];
                $obj->children[] = $tab;
            }
            $cascadeElements[] = $obj;
        }
        return $cascadeElements;
    }
    public function getCascadePages()
    {
        $pages = $this->model->where('active', 1)->get();
        // create cascade array
        $cascadeElements = [];
        foreach($pages as $page)
        {
            $obj = new \stdClass();
            $obj->value = $page->id;
            $obj->label = $page->name;
            $obj->children = [];
            $cascadeElements[] = $obj;
        }
        return $cascadeElements;
    }
    public function getCascadeComponents()
    {
        $pagesWithComponents = $this->model->where('active', 1)->with('components')->get();
        // create cascade array
        $cascadeElements = [];
        foreach($pagesWithComponents as $pageWithComponents)
        {
            //if (count($pageWithComponents->components) == 0) continue;
            $obj = new \stdClass();
            $obj->value = $pageWithComponents->id;
            $obj->label = $pageWithComponents->name;
            $obj->children = [];
            foreach($pageWithComponents->components as $component)
            {
                $pageComponent = new \stdClass();
                $pageComponent->value = $component->id;
                $pageComponent->label = $component->name;
                $pageComponent->children = [];
                //if (count($component->child_components) == 0) continue;
                foreach($component->child_components as $child_component)
                {
                    $childComponent = new \stdClass();
                    $childComponent->value = $child_component->id;
                    $childComponent->label = $child_component->name;
                    $childComponent->children = [];
                    foreach($child_component->child_components as $child_child_component)
                    {
                        $childChildComponent = new \stdClass();
                        $childChildComponent->value = $child_child_component->id;
                        $childChildComponent->label = $child_child_component->name;
                        $childChildComponent->children = [];
                        $childComponent->children[] = $childChildComponent;
                    }
                    $pageComponent->children[] = $childComponent;
                }
                $obj->children[] = $pageComponent;
            }
            $cascadeElements[] = $obj;
        }
        return $cascadeElements;
    }
    public function getCascadeProcesses()
    {
        $pages = $this->model->where('active', 1)->with('components')->get();
        // create cascade array
        $cascadeElements = [];
        foreach($pages as $page)
        {
            if (count($page->components) == 0) continue;
            $obj = new \stdClass();
            $obj->value = $page->id;
            $obj->label = $page->name;
            $obj->children = [];
            foreach($page->components as $component)
            {
                if ( (count($component->child_components) == 0) && (count($component->processes) == 0)) continue;
                $pageComponent = new \stdClass();
                $pageComponent->value = $component->id;
                $pageComponent->label = $component->name;
                $pageComponent->children = [];
                foreach($component->processes as $process)
                {
                    $componentProcess =  new \stdClass();
                    $componentProcess->value = $process->id;
                    $componentProcess->label = $process->name;
                    $pageComponent->children[] = $componentProcess;
                }
                foreach($component->child_components as $child_component)
                {
                    if (count($child_component->processes) == 0) continue;
                    $childComponent =  new \stdClass();
                    $childComponent->value = $child_component->id;
                    $childComponent->label = $child_component->name;
                    $childComponent->children = [];
                    foreach($child_component->processes as $process)
                    {
                        $childProcess =  new \stdClass();
                        $childProcess->value = $process->id;
                        $childProcess->label = $process->name;
                        $childComponent->children[] = $childProcess;
                    }
                    foreach($child_component->child_components as $child_child_component)
                    {
                        $childChildComponent = new \stdClass();
                        $childChildComponent->value = $child_child_component->id;
                        $childChildComponent->label = $child_child_component->name;
                        $childChildComponent->children = [];
                        foreach($child_child_component->processes as $childChildComponentProcess)
                        {
                            $childChildProcess =  new \stdClass();
                            $childChildProcess->value = $childChildComponentProcess->id;
                            $childChildProcess->label = $childChildComponentProcess->name;
                            $childChildComponent->children[] = $childChildProcess;
                        }
                        if (count($childChildComponent->children) == 0) continue;
                        $childComponent->children[] = $childChildComponent;
                    }
                    $pageComponent->children[] = $childComponent;
                }

                $obj->children[] = $pageComponent;
            }
            if (count($obj->children) == 0) continue;
            $cascadeElements[] = $obj;
        }
        return $cascadeElements;
    }
}