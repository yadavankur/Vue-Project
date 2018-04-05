<?php

namespace App\Models\Repositories;

use App\Models\Entities\CustomerTierLevel;

class CustomerTierLevelRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\CustomerTierLevel';
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
        $customerTierLevel =  new CustomerTierLevel();
        $customerTierLevel->name = $request->input('name');
        $customerTierLevel->comment = $request->input('comment');
        $customerTierLevel->save();
        return $customerTierLevel;
    }
    public function save($request)
    {
        $customerTierLevel =  $this->model->findOrFail($request->input('id'));
        $customerTierLevel->name = $request->input('name');
        $customerTierLevel->comment = $request->input('comment');
        $customerTierLevel->save();
        return $customerTierLevel;
    }
    public function delete($request)
    {
        $customerTierLevel =  $this->model->findOrFail($request->input('id'));
        $customerTierLevel->active = 0;
        $customerTierLevel->save();
        return $customerTierLevel;
    }

}