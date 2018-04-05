<?php

namespace App\Models\Services;


use App\Models\Repositories\CpmActivityRepository;
use Illuminate\Http\Request;

class CpmActivityService extends BaseService
{

    protected $cpmActivityRepository;

    public function __construct(CpmActivityRepository $cpmActivityRepository)
    {
        $this->cpmActivityRepository = $cpmActivityRepository;
    }
    public function getByPaginate(Request $request, $userService)
    {
        return $this->cpmActivityRepository->getByPaginate($request, $userService);
    }
    public function update($request)
    {
        return $this->cpmActivityRepository->save($request);
    }
    public function add($request)
    {
        return $this->cpmActivityRepository->add($request);
    }
    public function delete($request)
    {
        return $this->cpmActivityRepository->delete($request);
    }
    public function getCpmActivityOptions(Request $request)
    {
        return $this->cpmActivityRepository->getCpmActivityOptions($request);
    }
    public function getAssociatedUserOptions(Request $request)
    {
        return $this->cpmActivityRepository->getAssociatedUserOptions($request);
    }
    public function getAssociatedGroupOptions(Request $request)
    {
        return $this->cpmActivityRepository->getAssociatedGroupOptions($request);
    }
    public function getAssociatedUsers(Request $request)
    {
        return $this->cpmActivityRepository->getAssociatedUsers($request);
    }
    public function getAssociatedManagers(Request $request)
    {
        return $this->cpmActivityRepository->getAssociatedManagers($request);
    }
    public function updateAssociatedUser(Request $request)
    {
        return $this->cpmActivityRepository->updateAssociatedUser($request);
    }
}