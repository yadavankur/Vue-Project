<?php

namespace App\Models\Services;


use App\Models\Repositories\ScreenActivityMappingRepository;

class ScreenActivityMappingService extends BaseService
{
    protected $screenActivityMappingRepository;
    public function __construct(ScreenActivityMappingRepository $screenActivityMappingRepository)
    {
        $this->screenActivityMappingRepository = $screenActivityMappingRepository;
    }
    public function getByPaginate($request)
    {
        return $this->screenActivityMappingRepository->getByPaginate($request);
    }
}