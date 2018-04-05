<?php

namespace App\Models\Services;


use App\Models\Repositories\CpmServiceGroupRepository;
use Illuminate\Http\Request;

class CpmServiceGroupService extends BaseService
{
    protected $cpmServiceGroupRepository;

    public function __construct(CpmServiceGroupRepository $cpmServiceGroupRepository)
    {
        $this->cpmServiceGroupRepository = $cpmServiceGroupRepository;
    }
    public function getByPaginate(Request $request)
    {
        return $this->cpmServiceGroupRepository->getByPaginate($request);
    }
    public function updateCpmServiceGroup($request)
    {
        return $this->cpmServiceGroupRepository->updateCpmServiceGroup($request);
    }
    public function getServiceGroupOptions($request)
    {
        return $this->cpmServiceGroupRepository->getServiceGroupOptions($request);
    }
}