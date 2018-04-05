<?php

namespace App\Models\Services;


use App\Models\Repositories\ResourceTypeRepository;
use Illuminate\Http\Request;

class ResourceTypeService extends BaseService
{
    protected $resourceTypeRepository;

    public function __construct(ResourceTypeRepository $resourceTypeRepository)
    {
        $this->resourceTypeRepository = $resourceTypeRepository;
    }
    public function getResourceTypeOptions(Request $request)
    {
        $resourceTypeOptions = $this->resourceTypeRepository->findAllBy('active',1);
        return $resourceTypeOptions;
    }
    public function getByPaginate(Request $request)
    {
        return $this->resourceTypeRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->resourceTypeRepository->save($request);
    }
    public function add($request)
    {
        return $this->resourceTypeRepository->add($request);
    }
    public function delete($request)
    {
        return $this->resourceTypeRepository->delete($request);
    }

}