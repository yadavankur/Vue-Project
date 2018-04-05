<?php

namespace App\Models\Services;


use App\Models\Repositories\CpmServiceRepository;
use Illuminate\Http\Request;

class CpmServiceService extends BaseService
{

    protected $cpmServiceRepository;

    public function __construct(CpmServiceRepository $cpmServiceRepository)
    {
        $this->cpmServiceRepository = $cpmServiceRepository;
    }
    public function getByPaginate(Request $request)
    {
        return $this->cpmServiceRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->cpmServiceRepository->save($request);
    }
    public function add($request)
    {
        return $this->cpmServiceRepository->add($request);
    }
    public function delete($request)
    {
        return $this->cpmServiceRepository->delete($request);
    }
    public function getCpmServiceOptions(Request $request)
    {
        $componentOptions = $this->cpmServiceRepository->findAllBy('active', 1);
        return $componentOptions;
    }
    public function getCpmCascadeServiceOptions($serviceOptions)
    {
        $componentOptions = $this->cpmServiceRepository->getCpmCascadeServiceOptions($serviceOptions);
        return $componentOptions;
    }
    public function getCpmServiceOptionsOfLocation($request)
    {
        return $this->cpmServiceRepository->getCpmServiceOptionsOfLocation($request);
    }
    public function getCpmCascadeServiceGroupOptions($serviceOptions)
    {
        $componentOptions = $this->cpmServiceRepository->getCpmCascadeServiceGroupOptions($serviceOptions);
        return $componentOptions;
    }
    public function getCpmServiceGroupOptionsOfLocation($request)
    {
        return $this->cpmServiceRepository->getCpmServiceGroupOptionsOfLocation($request);
    }
}