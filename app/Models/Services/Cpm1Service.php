<?php

namespace App\Models\Services;


use App\Models\Repositories\Cpm1Repository;
use Illuminate\Http\Request;

class Cpm1Service extends BaseService
{
    protected $cpm1Repository;
    public function __construct(Cpm1Repository $cpm1Repository)
    {
        $this->cpm1Repository = $cpm1Repository;
    }
    public function getByPaginate(Request $request, $userService)
    {
        return $this->cpm1Repository->getByPaginate($request, $userService);
    }
    public function delete($request)
    {
        return $this->cpm1Repository->delete($request);
    }

}