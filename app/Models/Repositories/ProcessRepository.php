<?php

namespace App\Models\Repositories;

use App\Models\Entities\Component;
use App\Models\Entities\Page;
use App\Models\Entities\Process;
use DB;
use Exception;

class ProcessRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\Process';
    }

    public function getAll($componentId, $user)
    {
        $isAdmin = self::isAdmin($user);
        if ($isAdmin)
            return $this->model->where('active', 1)->where('component_id', $componentId)
                ->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
        else
            return $this->model->where('active', 1)->where('component_id', $componentId)
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

        $query = $this->model->select('processes.*')
            ->join('components', 'components.id', '=', 'processes.component_id')
            ->orderBy($sortBy, $sortDirection)
            ->where('processes.active',1)->with('component');

        if ($search) {
            $like = "%{$search}%";

            $query = $query->where(function ($query) use ($like) {
                $query->where('processes.name', 'LIKE', $like)
                    ->orWhere('components.name','LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
    public function add($request)
    {
        $process =  new Process();
        $process->name = $request->input('name');
        $process->comment = $request->input('comment');
        $componentId = $request->input('component.id');
        $pageId =  $request->input('page.id');
        $page = Page::where('id', $pageId)->where('active',1)->first();
        if (! $page instanceof Page)
        {
            throw new Exception('The page is invalid!');
        }
        $component = Component::where('id', $componentId)
            ->where('active',1)->first();
        if (! $component instanceof Component)
        {
            throw new Exception('The component is invalid!');
        }
        $process->component_id =$component->id;
        $process->save();
        return $process;
    }
    public function save($request)
    {
        $process =  $this->model->findOrFail($request->input('id'));
        $process->name = $request->input('name');
        $process->comment = $request->input('comment');
        $componentId = $request->input('component.id');
        $pageId =  $request->input('page.id');

        $page = Page::where('id', $pageId)->where('active',1)->first();
        if (! $page instanceof Page)
        {
            throw new Exception('The page is invalid!');
        }
        $component = Component::where('id', $componentId)
            ->where('active',1)->first();
        if (! $component instanceof Component)
        {
            throw new Exception('The component is invalid!');
        }
        $process->component_id =$component->id;
        $process->save();
        return $process;
    }
    public function delete($request)
    {
        $process = $this->model->findOrFail($request->input('id'));
        $process->active = 0;
        $process->save();
        return $process;
    }
}