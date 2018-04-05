<?php

namespace App\Models\Services;


use App\Models\Repositories\OrderDetailRepository;
use Illuminate\Http\Request;

class OrderDetailService extends BaseService
{
    protected $orderDetailRepository;

    public function __construct(OrderDetailRepository $orderDetailRepository)
    {
        $this->orderDetailRepository = $orderDetailRepository;
    }
    public function getDetails(Request $request)
    {
        return $this->orderDetailRepository->getOrderDetails($request);
    }
}