<?php
namespace App\Models\Repositories;

use DB;

class ResourceRepository extends BaseRepository
{
    protected $resourceClass;

    public function model()
    {
        return 'App\Models\Entities\ResourceType';
    }

}