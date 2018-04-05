<?php

namespace App\Models\Services;

use App\Models\Repositories\OrderListRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderListService extends BaseService
{
    protected $orderListRepository;

    public function __construct(OrderListRepository $orderListRepository)
    {
        $this->orderListRepository = $orderListRepository;
    }
    public function getByPaginate($request, $userService)
    {
        return $this->orderListRepository->getByPaginate($request, $userService);
    }
    public function getStatusOptions($request)
    {
        return $this->orderListRepository->getStatusOptions($request);
    }
    public function getOrderDetailOnSearch($request, $userService)
    {
        return $this->orderListRepository->getOrderDetailOnSearch($request, $userService);
    }

}