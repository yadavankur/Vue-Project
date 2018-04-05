<?php

namespace App\Models\Services;

use App\Models\Repositories\AssociatedJobRepository;
use App\Models\Repositories\OrderDetailRepository;
use Illuminate\Http\Request;

class AssociatedJobService extends BaseService
{
    protected $associatedJobRepository;
    protected $orderDetailRepository;
    public function __construct(AssociatedJobRepository $associatedJobRepository,
                                OrderDetailRepository $orderDetailRepository)
    {
        $this->associatedJobRepository = $associatedJobRepository;
        $this->orderDetailRepository = $orderDetailRepository;
    }

    public function getByPaginate(Request $request)
    {
        return $this->associatedJobRepository->getByPaginate($request);
    }
    public function getAllByPaginate(Request $request)
    {
        return $this->orderDetailRepository->getAllAssociatedJobsByPaginate($request);
    }
    public function add($request)
    {
        return $this->associatedJobRepository->add($request);
    }
    public function delete($request)
    {
        return $this->associatedJobRepository->delete($request);
    }
    public function update($request) {
        return $this->associatedJobRepository->save($request);
    }
    public function printConfirmationList($request) {
        return $this->associatedJobRepository->printConfirmationList($request);
    }

}