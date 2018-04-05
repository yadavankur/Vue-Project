<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use DB;

class ResourceTypeRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\ResourceType';
    }
    public function getResourceTypeOptions()
    {

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
        $resourceType =  new ResourceType();
        $resourceType->name = $request->input('name');
        $resourceType->comment = $request->input('comment');
        $resourceType->save();
        return $resourceType;
    }
    public function save($request)
    {
        $resourceType =  $this->model->findOrFail($request->input('id'));
        $resourceType->name = $request->input('name');
        $resourceType->comment = $request->input('comment');
        $resourceType->save();
        return $resourceType;
    }
    public function delete($request)
    {
        $resourceType =  $this->model->findOrFail($request->input('id'));
        $resourceType->active = 0;
        $resourceType->save();
        return $resourceType;
    }
}