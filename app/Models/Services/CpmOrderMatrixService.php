<?php

namespace App\Models\Services;


use App\Models\Repositories\CpmOrderMatrixRepository;
use Illuminate\Http\Request;

class CpmOrderMatrixService extends BaseService
{
    protected $cpmOrderMatrixRepository;
    public function __construct(CpmOrderMatrixRepository $cpmOrderMatrixRepository)
    {
        $this->cpmOrderMatrixRepository = $cpmOrderMatrixRepository;
    }
    public function getByPaginate(Request $request)
    {
        return $this->cpmOrderMatrixRepository->getByPaginate($request);
    }
    public function getAllActivitiesOfOrder($request)
    {
        return $this->cpmOrderMatrixRepository->getAllActivitiesOfOrder($request);
    }
    public function calculateCPM($request)
    {
        return $this->cpmOrderMatrixRepository->calculateCPM($request);
    }
    public function reCalculateCPM($request)
    {
        return $this->cpmOrderMatrixRepository->simulateCPM($request);
    }
    public function updateCPM($request)
    {
        return $this->cpmOrderMatrixRepository->updateCPM($request);
    }
    public function createCPM($request)
    {
        return $this->cpmOrderMatrixRepository->createCPM($request);
    }
    public function addAdhocActivity($request)
    {
        return $this->cpmOrderMatrixRepository->addAdhocActivity($request);
    }
    public function getAllTasks($request)
    {
        return $this->cpmOrderMatrixRepository->getAllTasks($request);
    }
    public function getAssociatedTasks($request)
    {
        return $this->cpmOrderMatrixRepository->getAssociatedTasksByPaginate($request);
    }
}