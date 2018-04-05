<?php

namespace App\Models\Entities;

class CPM_Service extends BaseModel
{
    // this is a must for each location the major service name
    // must be main service
    const MAIN_SERVICE = 'main service'; //--mark greenwood
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cpm_services';
    protected $with=['service_groups'];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id')->where('active', 1);
    }
    public function service_groups()
    {
        return $this->hasMany(CPM_ServiceGroup::class, 'service_id', 'id')->where('active', 1);
    }

}