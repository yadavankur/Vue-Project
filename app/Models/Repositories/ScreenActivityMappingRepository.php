<?php

namespace App\Models\Repositories;

class ScreenActivityMappingRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\ScreenActivityMapping';
    }
    public function getByPaginate($request)
    {
        // TODO
        return;
    }
}