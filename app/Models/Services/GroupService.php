<?php

namespace App\Models\Services;

use App\Models\Repositories\GroupRepository;
use Illuminate\Http\Request;

class GroupService extends BaseService
{
    protected $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function getAll()
    {
        return $this->groupRepository->getAll();
    }
    public function usersaspergroups()
    {
        return $this->groupRepository->usersaspergroups();
    }
    public function getByPaginate(Request $request)
    {
        return $this->groupRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->groupRepository->save($request);
    }
    public function add($request)
    {
        return $this->groupRepository->add($request);
    }
    public function delete($request)
    {
        return $this->groupRepository->delete($request);
    }
}