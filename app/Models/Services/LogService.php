<?php

namespace App\Models\Services;


use App\Models\Repositories\LogRepository;

class LogService extends BaseService
{
    protected $logRepository;
    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }
    public function getByPaginate($request)
    {
        return $this->logRepository->getByPaginate($request);
    }
}