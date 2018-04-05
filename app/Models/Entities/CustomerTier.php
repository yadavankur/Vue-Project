<?php

namespace App\Models\Entities;


class CustomerTier extends BaseModel
{
    protected $table = 'customer_tiers';
    public function customer_tier_level()
    {
        return $this->belongsTo(CustomerTierLevel::class, 'tier_id', 'id')->where('active', 1);
    }
    public function customer()
    {
        return $this->belongsTo(V6Customer::class, 'customer_id', 'CUST_ID');
    }
}