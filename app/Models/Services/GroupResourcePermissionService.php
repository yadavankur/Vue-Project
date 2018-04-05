<?php

namespace App\Models\Services;

use App\Models\Repositories\GroupRepository;
use App\Models\Repositories\GroupResourcePermissionRepository;
use App\Models\Repositories\RoleRepository;
use App\Models\Repositories\UserRepository;
use Illuminate\Http\Request;

class GroupResourcePermissionService extends BaseService
{
    protected $groupResourcePermissionRepository;
    protected $userRepository;
    protected $roleRepository;
    protected $groupRepository;

    public function __construct(GroupResourcePermissionRepository $groupResourcePermissionRepository,
                                UserRepository $userRepository,
                                GroupRepository $groupRepository,
                                RoleRepository $roleRepository)
    {
        $this->groupResourcePermissionRepository = $groupResourcePermissionRepository;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->groupRepository = $groupRepository;
    }
    public function update($request)
    {
        return $this->groupResourcePermissionRepository->save($request);
    }
    public function add($request)
    {
        return $this->groupResourcePermissionRepository->add($request);
    }
    public function delete($request)
    {
        return $this->groupResourcePermissionRepository->delete($request);
    }
    public function getByPaginate(Request $request)
    {
        return $this->groupResourcePermissionRepository->getByPaginate($this->roleRepository, $this->groupRepository, $request);
    }

}