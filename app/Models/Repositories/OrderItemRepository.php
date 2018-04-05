<?php

namespace App\Models\Repositories;

use DB;

class OrderItemRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\V6QuoteItem';
    }
    public function getOrderItems($request)
    {
        $quoteId = $request->quoteId;
        $quoteVer = $request->quoteVer;
        $items = $this->model->where('QUOTE_ID',$quoteId)->where('QUOTE_VERS_STOP', $quoteVer)
                    ->orderby('QTE_POS', 'asc')->get();
        return $items;
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;

        $quoteId = $request->quoteId;
        $quoteVer = $request->quoteVer;

        $search = $request->filter;

        $query = $this->model->where('QUOTE_ID',$quoteId)
            ->where('QUOTE_VERS_STOP', $quoteVer)
            ->orderBy($sortBy, $sortDirection);


        if ($search) 
        {  $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) {
                $query->where('FRA_CODE', 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }

}