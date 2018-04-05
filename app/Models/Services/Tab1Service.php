<?php

namespace App\Models\Services;

use App\Models\Entities\tab1;
use App\Models\Repositories\Tab1Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Tab1Service extends BaseService
{
    protected $tab1Repository;

    public function __construct(Tab1Repository $tab1Repository)
    {
        $this->tab1Repository = $tab1Repository;
    }

    public function getAll($user)
    {
        return $this->tab1Repository->getAll($user);
    }
    public function getByPaginate(Request $request)
    {
        return $this->tab1Repository->getByPaginate($request);
    }


}