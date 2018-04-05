<?php

namespace App\Models\Entities;

class CPM_OrderMatrix extends BaseModel
{

    const STATUS_NEW = 1;
    const STATUS_DELAY = 2;
    const STATUS_DEFERRAL = 3;
    const STATUS_ON_TIME = 4;
    const STATUS_COMPLETED = 5;

    const STATUSES = array(
        self::STATUS_NEW =>  'NEW',
        self::STATUS_DELAY  =>  'DELAY',
        self::STATUS_DEFERRAL =>  'DEFERRAL',
        self::STATUS_ON_TIME =>  'ON_TIME',
        self::STATUS_COMPLETED =>  'COMPLETED',
    );
    protected $dates = ['start_date', 'end_date', 'delivery_date', 'created_at', 'updated_at'];
    protected $table = 'cpm_order_matrixes';
    /***
     * get all status
     * @return array
     */
    public static function getStatuses()
    {
        return array(
            array('value' => self::STATUS_NEW, 'label' =>  'NEW'),
            array('value' => self::STATUS_DELAY, 'label' =>  'DELAY'),
            array('value' => self::STATUS_DEFERRAL, 'label' =>  'DEFERRAL'),
            array('value' => self::STATUS_ON_TIME, 'label' =>  'ON_TIME'),
            array('value' => self::STATUS_COMPLETED, 'label' =>  'COMPLETED'),
        );
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id')->where('active', 1);
    }
    public function service()
    {
        return $this->belongsTo(CPM_Service::class, 'service_id', 'id')->with('location')->where('active', 1);
    }
    public function order()
    {
        //return $this->belongsTo(V6Quote::class, 'order_id', 'UDF1')
        return $this->belongsTo(V6Quote::class, 'quote_id', 'QUOTE_ID')
            ->with('customer')
            ->with('sales_person')
            ->with('contact')
            ->with('status')
            ->with('deliver_address');
    }
    public function activity()
    {
        return $this->belongsTo(CPM_Activity::class, 'activity_id', 'id')->where('active', 1);
    }
    public function associatedUserUsers()
    {
        return $this->hasMany(CPMActivityUser::class, 'cpm_activity_id', 'activity_id')
            ->where('active',1)
            ->where('owner_type', BaseModel::OWNER_TYPE_USER)
            ->with('user');
    }
    public function associatedUserGroups()
    {
        return $this->hasMany(CPMActivityGroup::class, 'cpm_activity_id', 'activity_id')
            ->where('active',1)
            ->where('owner_type', BaseModel::OWNER_TYPE_USER)
            ->with('group');
    }
    public function associatedManagerUsers()
    {
        return $this->hasMany(CPMActivityUser::class, 'cpm_activity_id', 'activity_id')
            ->where('active',1)
            ->where('owner_type', BaseModel::OWNER_TYPE_MANAGER)
            ->with('user');
    }
    public function associatedManagerGroups()
    {
        return $this->hasMany(CPMActivityGroup::class, 'cpm_activity_id', 'activity_id')
            ->where('active',1)
            ->where('owner_type', BaseModel::OWNER_TYPE_MANAGER)
            ->with('group');
    }
}