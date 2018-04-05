<?php

namespace App\Models\Services;


use App\Models\Repositories\V6BomFillRepository;
use Illuminate\Http\Request;

class V6BomFillService extends BaseService
{
    protected $v6BomFillRepository;

    public function __construct(V6BomFillRepository $v6BomFillRepository)
    {
        $this->v6BomFillRepository = $v6BomFillRepository;
    }
    public function getByPaginate(Request $request)
    {
        return $this->v6BomFillRepository->getByPaginate($request);
    }
}