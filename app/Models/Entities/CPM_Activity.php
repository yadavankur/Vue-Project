<?php

namespace App\Models\Entities;

class CPM_Activity extends BaseModel
{
    const TYPE_BOOKING = 1;
    const TYPE_TRANSFER = 2;
    const TYPE_REVIEW_SERVICE = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cpm_activities';
    protected $casts = [
        'is_full' => 'boolean',
        'tick_option' => 'boolean',
    ];
    protected $with=['service_group_activity'];

    public function service()
    {
        return $this->belongsTo(CPM_Service::class, 'service_id', 'id')->where('active', 1)->with('location');
    }

    public function service_group_activity()
    {
        return $this->belongsTo(CPM_ServiceGroupActivity::class, 'id', 'activity_id')->where('active', 1)->with('service_group');
    }
    /***
     * get all service types
     * @return array
     */
    public static function getActivityTypes()
    {
        return array(
            self::TYPE_BOOKING => 'BOOKING',
            self::TYPE_TRANSFER => 'TRANSFER',
            self::TYPE_REVIEW_SERVICE => 'REVIEW_SERVICE',
        );
    }
}
