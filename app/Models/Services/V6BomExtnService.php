<?php

namespace App\Models\Services;


use App\Models\Repositories\V6BomExtnRepository;
use Illuminate\Http\Request;

class V6BomExtnService extends BaseService
{
    protected $v6BomExtnRepository;

    public function __construct(V6BomExtnRepository $v6BomExtnRepository)
    {
        $this->v6BomExtnRepository = $v6BomExtnRepository;
    }
    public function getByPaginate(Request $request)
    {
        return $this->v6BomExtnRepository->getByPaginate($request);
    }
}