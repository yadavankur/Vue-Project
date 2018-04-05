<?php
namespace App\Models\Services;

use App\Models\Repositories\PermissionRepository;
use Illuminate\Http\Request;

class PermissionService extends BaseService
{
    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getAll()
    {
        return $this->permissionRepository->getAll();
    }
    public function getByPaginate(Request $request)
    {
        return $this->permissionRepository->getByPaginate($request);
    }
    public function update($request)
    {
        return $this->permissionRepository->save($request);
    }
    public function add($request)
    {
        return $this->permissionRepository->add($request);
    }
    public function delete($request)
    {
        return $this->permissionRepository->delete($request);
    }
    public function getPermissionOptions(Request $request)
    {
        $permissionOptions = $this->permissionRepository->findAllBy('active',1);
        return $permissionOptions;
    }
}