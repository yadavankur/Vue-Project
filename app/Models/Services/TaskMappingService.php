<?php

namespace App\Models\Services;


use App\Models\Repositories\TaskMappingRepository;

class TaskMappingService extends BaseService
{
    protected $taskMappingRepository;

    public function __construct(TaskMappingRepository $taskMappingRepository)
    {
        $this->taskMappingRepository = $taskMappingRepository;
    }
    public function getByPaginate($request)
    {
        return $this->taskMappingRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->taskMappingRepository->save($request);
    }
}