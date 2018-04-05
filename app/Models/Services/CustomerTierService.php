<?php

namespace App\Models\Services;


use App\Models\Repositories\CustomerTierRepository;
use Illuminate\Http\Request;

class CustomerTierService extends BaseService
{
    protected $customerTierRepository;

    public function __construct(CustomerTierRepository $customerTierRepository)
    {
        $this->customerTierRepository = $customerTierRepository;
    }
    public function getByPaginate(Request $request)
    {
        return $this->customerTierRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->customerTierRepository->save($request);
    }
    public function add($request)
    {
        return $this->customerTierRepository->add($request);
    }
    public function delete($request)
    {
        return $this->customerTierRepository->delete($request);
    }
}