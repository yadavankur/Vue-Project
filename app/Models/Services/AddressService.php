<?php

namespace App\Models\Services;


use App\Models\Repositories\AddressRepository;

class AddressService extends BaseService
{
    protected $addressRepository;
    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }
    public function changeAddress($request)
    {
        return $this->addressRepository->changeAddress($request);
    }
}