<?php

namespace App\Models\Repositories;

use DB;

class V6BomFillRepository extends BaseRepository
{
    public function model() {
        return 'App\Models\Entities\V6BomFill';
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
        $orderId = $request->orderId;

        $filter = $request->filter;
        $search = '';
        $itemNo = '';
        if ($filter)
        {
            $itemNo = $filter['itemNo'];
            $search = $filter['filterText'];
        }

        $query = $this->model->select([
            'V_V6_QUOTE_ITEM.QTE_POS',
            'V_V6_BOM_FILL.*',
            'V_OOPS_FILL_IMP.DLDate'
            ])
            ->join('V_V6_QUOTE_ITEM', 'V_V6_BOM_FILL.QUOTE_ITEM_ID', '=', 'V_V6_QUOTE_ITEM.QUOTE_ITEM_ID')
            ->join('V_V6_QUOTE', function($join) {
                $join->on('V_V6_QUOTE.QUOTE_ID', '=', 'V_V6_QUOTE_ITEM.QUOTE_ID');
                $join->on('V_V6_QUOTE.QUOTE_VERS', '=', 'V_V6_QUOTE_ITEM.QUOTE_VERS_STOP');
            })
            ->leftJoin('V_OOPS_FILL_IMP', function($join) {
                $join->on('V_OOPS_FILL_IMP.SO', '=', 'V_V6_QUOTE.UDF1');
                $join->on(DB::raw('V_OOPS_FILL_IMP.POM COLLATE DATABASE_DEFAULT'), '=', DB::raw('V_V6_QUOTE.QUOTE_NUM_PREF COLLATE DATABASE_DEFAULT'));
                $join->on('V_OOPS_FILL_IMP.ItemNo', '=', 'V_V6_QUOTE_ITEM.QTE_POS');
            })
            ->where('V_V6_QUOTE.QUOTE_ID', $quoteId)
            ->where('V_V6_QUOTE.QUOTE_VERS', $quoteVer)
            ->where('V_V6_QUOTE.UDF1', $orderId)
            ->orderBy($sortBy, $sortDirection);

        if ($itemNo) {
            $query->where('V_V6_QUOTE_ITEM.QTE_POS', $itemNo);
        }

        if ($search) {
            $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) {
                $query->where('V_V6_BOM_FILL.DESCR', 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
}