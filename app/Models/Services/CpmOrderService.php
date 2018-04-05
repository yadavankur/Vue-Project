<?php

namespace App\Models\Services;


use App\Models\Repositories\CpmOrderRepository;
use Illuminate\Http\Request;

class CpmOrderService extends BaseService
{
    protected $cpmOrderRepository;
    public function __construct(CpmOrderRepository $cpmOrderRepository)
    {
        $this->cpmOrderRepository = $cpmOrderRepository;
    }
    public function getByPaginate(Request $request)
    {
        return $this->cpmOrderRepository->getByPaginate($request);
    }
    public function getSystemDeliveryDate(Request $request)
    {
        return $this->cpmOrderRepository->getSystemGeneratedDateList($request);
    }
    public function createOrderActivities(Request $request)
    {
        return $this->cpmOrderRepository->createOrderActivities($request);
    }

}