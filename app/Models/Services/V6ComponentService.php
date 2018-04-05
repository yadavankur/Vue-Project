<?php

namespace App\Models\Services;

use App\Models\Repositories\V6ComponentRepository;
use Illuminate\Http\Request;

class V6ComponentService extends BaseService
{
    protected $v6ComponentRepository;

    public function __construct(V6ComponentRepository $v6ComponentRepository)
    {
        $this->v6ComponentRepository = $v6ComponentRepository;
    }
    public function getByPaginate(Request $request)
    {
        return $this->v6ComponentRepository->getByPaginate($request);
    }

}