<?php

namespace App\Models\Services;


use App\Models\Repositories\LocationRepository;
use Illuminate\Http\Request;

class LocationService extends BaseService
{
    protected $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    public function getAll($user)
    {
        return $this->locationRepository->getAll($user);
    }
    public function getByPaginate(Request $request)
    {
        return $this->locationRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->locationRepository->save($request);
    }
    public function add($request)
    {
        return $this->locationRepository->add($request);
    }
    public function delete($request)
    {
        return $this->locationRepository->delete($request);
    }
    public function getLocationOptions()
    {
        $stateOptions = $this->locationRepository->findAllBy('active',1);
        return $stateOptions;
    }

}