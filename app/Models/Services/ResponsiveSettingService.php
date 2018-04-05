<?php

namespace App\Models\Services;

use App\Models\Repositories\ResponsiveSettingRepository;
use Illuminate\Http\Request;

class ResponsiveSettingService extends BaseService
{
    protected $responsiveSettingRepository;

    public function __construct(ResponsiveSettingRepository $responsiveSettingRepository)
    {
        $this->responsiveSettingRepository = $responsiveSettingRepository;
    }

    public function getAll()
    {
        return $this->responsiveSettingRepository->getAll();
    }
    public function getByPaginate(Request $request)
    {
        return $this->responsiveSettingRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->responsiveSettingRepository->save($request);
    }
    public function add($request)
    {
        return $this->responsiveSettingRepository->add($request);
    }
    public function delete($request)
    {
        return $this->responsiveSettingRepository->delete($request);
    }
}