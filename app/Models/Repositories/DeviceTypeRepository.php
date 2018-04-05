<?php

namespace App\Models\Repositories;


use App\Models\Entities\DeviceType;
use DB;

class DeviceTypeRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\DeviceType';
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
        $deviceType =  new DeviceType();
        $deviceType->name = $request->input('name');
        $deviceType->comment = $request->input('comment');
        $deviceType->save();
        return $deviceType;
    }
    public function save($request)
    {
        $deviceType =  $this->model->findOrFail($request->input('id'));
        $deviceType->name = $request->input('name');
        $deviceType->comment = $request->input('comment');
        $deviceType->save();
        return $deviceType;
    }
    public function delete($request)
    {
        $deviceType =  $this->model->findOrFail($request->input('id'));
        $deviceType->active = 0;
        $deviceType->save();
        return $deviceType;
    }
}