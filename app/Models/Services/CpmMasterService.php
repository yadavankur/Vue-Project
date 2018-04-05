<?php

namespace App\Models\Services;


use App\Models\Repositories\CpmMasterRepository;
use Illuminate\Http\Request;

class CpmMasterService extends BaseService
{

    protected $cpmMasterRepository;

    public function __construct(CpmMasterRepository $cpmMasterRepository)
    {
        $this->cpmMasterRepository = $cpmMasterRepository;
    }
    public function getByPaginate(Request $request, $userService)
    {
        return $this->cpmMasterRepository->getByPaginate($request,$userService);
    }
    public function update($request)
    {
        return $this->cpmMasterRepository->save($request);
    }
    public function add($request)
    {
        return $this->cpmMasterRepository->add($request);
    }
    public function delete($request)
    {
        return $this->cpmMasterRepository->delete($request);
    }
}