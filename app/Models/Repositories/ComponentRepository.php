<?php

namespace App\Models\Repositories;

use App\Models\Entities\Component;
use App\Models\Entities\Page;
use DB;
use Exception;

class ComponentRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\Component';
    }
    public function getComponentByName($componentName)
    {
        return $this->model->where('name', $componentName)->where('active', 1)->first();
    }
    public function getAll($pageId, $user)
    {
        $isAdmin = self::isAdmin($user);
        if ($isAdmin)
            return $this->model->where('active', 1)->where('page_id', $pageId)
                ->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
        else
            return $this->model->where('active', 1)->where('page_id', $pageId)
                ->get(['*', DB::raw("'H' as permission")])->keyBy('id')->toArray();
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;

        $search = $request->filter;

        $query = $this->model->select('components.*')
            ->join('pages', 'pages.id', '=', 'components.page_id')
            ->orderBy($sortBy, $sortDirection)
            ->where('components.active',1)
            ->where('pages.active',1)
            ->where('components.active',1)
            ->with('page')->with('parent');

        if ($search) {
            $like = "%{$search}%";

            $query = $query->where(function ($query) use ($like) {
                $query->where('components.name', 'LIKE', $like)
                    ->orWhere('pages.name','LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
    public function add($request)
    {
        $component =  new Component();
        $component->name = $request->input('name');
        $component->comment = $request->input('comment');
        $pageId = $request->input('page.value');
        $page = Page::where('id', $pageId)->where('active',1)->first();
        if (! $page instanceof Page)
        {
            throw new Exception('The page is invalid!');
        }
        $component->page_id =$page->id;
        $parentId = $request->input('parent.value');
        if ($parentId)
        {
            $parent = Component::where('id', $parentId)->where('active',1)->first();
            if (! $parent instanceof Component)
            {
                throw new Exception('The parent component is invalid!');
            }
            $component->parent_id = $parent->id;
        }
        else
            $component->parent_id = 0;

        $component->save();
        return $component;
    }
    public function save($request)
    {
        $component =  $this->model->findOrFail($request->input('id'));
        $component->name = $request->input('name');
        $component->comment = $request->input('comment');
        $pageId = $request->input('page.value');
        $page = Page::where('id', $pageId)->where('active',1)->first();
        if (! $page instanceof Page)
        {
            throw new Exception('The page is invalid!');
        }
        $component->page_id =$page->id;

        $parentId = $request->input('parent.value');
        if ($parentId)
        {
            $parent = Component::where('id', $parentId)->where('active',1)->first();
            if (! $parent instanceof Component)
            {
                throw new Exception('The parent component is invalid!');
            }
            if ($parent->id == $component->id)
            {
                throw new Exception('Sorry, you cannot choose yourself as a parent component!');
            }
            if ($parent->parent_id == $component->id)
            {
                throw new Exception('Sorry, you cannot choose your child as a parent component!');
            }
            $component->parent_id = $parent->id;
        }
        else
            $component->parent_id = 0;
        $component->save();
        return $component;
    }
    public function delete($request)
    {
        $component =  $this->model->findOrFail($request->input('id'));
        $component->active = 0;
        $component->save();
        return $component;
    }
    public function getAllComponentOptions()
    {
        $query = $this->model->select('components.*')
            ->join('pages', 'pages.id', '=', 'components.page_id')
            ->where('components.active',1)
            ->where('pages.active',1)
            ->where('components.active',1)
            ->with('page')->with('parent');
        return $query->get();
    }
    public function getCascadeComponentOptions()
    {
        $cascadeComponents = Page::where('active',1)->with('components')->get();
        // create cascade array
        $cascadeElements = [];
        foreach($cascadeComponents as $cascadeComponent)
        {
            $obj = new \stdClass();
            $obj->value = $cascadeComponent->id;
            $obj->label = $cascadeComponent->name;
            $obj->children = [];
            foreach($cascadeComponent->components as $component)
            {
                $child = new \stdClass();
                $child->value = $component->id;
                $child->label = $component->name;
                $child->children = [];
                foreach($component->child_components as $child_component)
                {
                    $child_child = new \stdClass();
                    $child_child->value = $child_component->id;
                    $child_child->label = $child_component->name;
                    $child->children[] = $child_child;
                }
                $obj->children[] = $child;
            }
            $cascadeElements[] = $obj;
        }
        return $cascadeElements;
    }
}