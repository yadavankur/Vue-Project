<?php

namespace App\Models\Services;


use App\Models\Repositories\V6CustomerContactRepository;
use Illuminate\Http\Request;

class V6CustomerContactService extends BaseService
{
    protected $v6CustomerContactRepository;

    public function __construct(V6CustomerContactRepository $v6CustomerContactRepository)
    {
        $this->v6CustomerContactRepository = $v6CustomerContactRepository;
    }
    public function getByPaginate(Request $request)
    {
        return $this->v6CustomerContactRepository->getByPaginate($request);
    }
    public function changeSupervisor(Request $request)
    {
        return $this->v6CustomerContactRepository->changeSupervisor($request);
    }
    public function newSupervisor(Request $request)
    {
        return $this->v6CustomerContactRepository->newSupervisor($request);
    }
}