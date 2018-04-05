<?php

namespace App\Models\Services;


use App\Models\Repositories\RoleRepository;
use Illuminate\Http\Request;

class RoleService extends BaseService
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    public function getChildRoles($user)
    {
        return $this->roleRepository->getChildRoles($user);
    }
    public function update($request)
    {
        return $this->roleRepository->save($request);
    }
    public function add($request)
    {
        return $this->roleRepository->add($request);
    }
    public function delete($request)
    {
        return $this->roleRepository->delete($request);
    }
    public function getByPaginate(Request $request)
    {
        return $this->roleRepository->getByPaginate($request);
    }
    public function getAllRoleOptions(Request $request)
    {
        return $this->roleRepository->getAllRoleOptions($request);
    }
}