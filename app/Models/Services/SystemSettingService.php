<?php

namespace App\Models\Services;


use App\Models\Repositories\SystemSettingRepository;

class SystemSettingService extends BaseService
{
    protected $systemSettingRepository;
    public function __construct(SystemSettingRepository $systemSettingRepository)
    {
        $this->systemSettingRepository = $systemSettingRepository;
    }
    public function getByPaginate($request)
    {
        return $this->systemSettingRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->systemSettingRepository->save($request);
    }
    public function getSettingByLocation($request)
    {
        return $this->systemSettingRepository->getSettingByLocation($request);
    }
}