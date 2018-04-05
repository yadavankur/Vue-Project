<?php

namespace App\Models\Services;

use App\Models\Repositories\CustomerTierLevelRepository;
use Illuminate\Http\Request;

class CustomerTierLevelService extends BaseService
{
    protected $customerTierLevelRepository;

    public function __construct(CustomerTierLevelRepository $customerTierLevelRepository)
    {
        $this->customerTierLevelRepository = $customerTierLevelRepository;
    }
    public function getByPaginate(Request $request)
    {
        return $this->customerTierLevelRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->customerTierLevelRepository->save($request);
    }
    public function add($request)
    {
        return $this->customerTierLevelRepository->add($request);
    }
    public function delete($request)
    {
        return $this->customerTierLevelRepository->delete($request);
    }
    public function getCustomerTierLevelOptions()
    {
        $customerTierLevelOptions = $this->customerTierLevelRepository->findAllBy('active', 1);
        return $customerTierLevelOptions;
    }
}