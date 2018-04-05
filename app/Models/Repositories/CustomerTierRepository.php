<?php

namespace App\Models\Repositories;

use App\Models\Entities\CustomerTier;
use Exception;
use Illuminate\Support\Facades\Auth;

class CustomerTierRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\CustomerTier';
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;

        $search = $request->filter;

        $query = $this->model->select(['customer_tiers.*', 'V_V6_CUSTOMER.*', 'customer_tier_levels.name'])
            ->join('V_V6_CUSTOMER', 'V_V6_CUSTOMER.CUST_ID', '=', 'customer_tiers.customer_id', 'right outer')
//            ->where('customer_tiers.active', 1)->orwhere('customer_tiers.active', null)
            ->join('customer_tier_levels', 'customer_tier_levels.id', '=', 'customer_tiers.tier_id',  'left outer')
            ->orderBy($sortBy, $sortDirection)
            ->with('customer')
            ->with('customer_tier_level');

//        $user = Auth::user();
//        $locationIds = LocationRepository::getAccessibleLocationIds($user);
//        $query->join('locations', 'locations.abbreviation', '=', 'V_V6_QUOTE.QUOTE_NUM_PREF', 'left outer')
//            ->wherein('locations.id', $locationIds);

        if ($search) {
            $like = "%{$search}%";

            $query = $query->where(function ($query) use ($like) {
                $query->where('V_V6_CUSTOMER.CUST_NAME', 'LIKE', $like)
                    ->orwhere('V_V6_CUSTOMER.CUST_CODE', 'LIKE', $like)
                    ->orwhere('V_V6_CUSTOMER.CLASSIF_CODE', 'LIKE', $like)
                    ->orwhere('customer_tier_levels.name', 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
    public function add($request)
    {
        $customerTier =  new CustomerTier();
        $customerTier->name = $request->input('name');
        $customerTier->comment = $request->input('comment');
        $customerTier->save();
        return $customerTier;
    }
    public function save($request)
    {
        $customerTierId = trim($request->input('id'));
        if ($customerTierId)
        {
            // update
            $customerTier =  $this->model->findOrFail($customerTierId);
            if (!$customerTier instanceof CustomerTier)
            {
                throw new Exception('Invalid customer!');
            }
        }
        else
        {
            // new
            $customerTier = new CustomerTier();
            $customerTier->customer_id = $request->input('customer.id');
        }
        $customerTier->tier_id = $request->input('tierLevel');
        $customerTier->comment = $request->input('comment');
        $customerTier->save();
        return $customerTier;
    }
    public function delete($request)
    {
        $customerTier =  $this->model->findOrFail($request->input('id'));
        $customerTier->active = 0;
        // physically delete it
        // or it will be difficult to
        // get the customer when there is a record
        // in customer_tiers table with active=0
        $customerTier->delete();
        return $customerTier;
    }

}