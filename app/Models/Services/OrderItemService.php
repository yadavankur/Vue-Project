<?php

namespace App\Models\Services;


use App\Models\Repositories\OrderItemRepository;
use Illuminate\Http\Request;
class OrderItemService extends BaseService
{
    protected $orderItemRepository;

    public function __construct(OrderItemRepository $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }
    public function getOrderItems(Request $request)
    {
        return $this->orderItemRepository->getOrderItems($request);
    }
    public function getByPaginate(Request $request)
    {
        return $this->orderItemRepository->getByPaginate($request);
    }
}