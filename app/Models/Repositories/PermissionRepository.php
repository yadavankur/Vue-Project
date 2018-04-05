<?php
namespace App\Models\Repositories;

use App\Models\Entities\Permission;
use DB;

class PermissionRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\Permission';
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
        $permission =  new Permission();
        $permission->name = $request->input('name');
        $permission->comment = $request->input('comment');
        $permission->save();
        return $permission;
    }
    public function save($request)
    {
        $permission =  $this->model->findOrFail($request->input('id'));
        $permission->name = $request->input('name');
        $permission->comment = $request->input('comment');
        $permission->save();
        return $permission;
    }
    public function delete($request)
    {
        $permission =  $this->model->findOrFail($request->input('id'));
        $permission->active = 0;
        $permission->save();
        return $permission;
    }
}