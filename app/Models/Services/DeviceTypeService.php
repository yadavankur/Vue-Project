<?php

namespace App\Models\Services;


use App\Models\Repositories\DeviceTypeRepository;
use Illuminate\Http\Request;

class DeviceTypeService extends BaseService
{

    protected $deviceTypeRepository;

    public function __construct(DeviceTypeRepository $deviceTypeRepository)
    {
        $this->deviceTypeRepository = $deviceTypeRepository;
    }

    public function getAll()
    {
        return $this->deviceTypeRepository->getAll();
    }
    public function getByPaginate(Request $request)
    {
        return $this->deviceTypeRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->deviceTypeRepository->save($request);
    }
    public function add($request)
    {
        return $this->deviceTypeRepository->add($request);
    }
    public function delete($request)
    {
        return $this->deviceTypeRepository->delete($request);
    }
    public function getDeviceTypeOptions(Request $request)
    {
        $componentOptions = $this->deviceTypeRepository->findAllBy('active', 1);
        return $componentOptions;
    }
}