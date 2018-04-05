<?php

namespace App\Models\Entities;

class BusinessCalendarHoliday extends BaseModel
{
    protected $table = 'business_calendar_holidays';
    //protected $dates = ['created_at', 'updated_at', 'value'];
    const TYPE_PUBLIC_HOLIDAY = 'PUBLIC_HOLIDAY';
    const TYPE_WORKING_DAY = 'WORKING_DAY';
    const TYPE_NONWORKING_DAY = 'NONWORKING_DAY';
    const TYPE_FREE_DAY = 'FREE_DAY';
    const TYPE_FREE_WEEK_DAY = 'FREE_WEEK_DAY';

    protected $with=[];
    protected $fillable = ['state_id', 'location_id', 'year', 'type', 'value', 'description', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id')->where('active', 1);
    }

    public static function getTypes($type = 'Default')
    {
        if ($type == 'Default')
        {
            return array(
                array('value' => self::TYPE_WORKING_DAY, 'label' =>  self::TYPE_WORKING_DAY),
                array('value' => self::TYPE_NONWORKING_DAY, 'label' =>  self::TYPE_NONWORKING_DAY),
            );
        }
        else
        {
            return array(
                array('value' => self::TYPE_PUBLIC_HOLIDAY, 'label' =>  self::TYPE_PUBLIC_HOLIDAY),
                array('value' => self::TYPE_WORKING_DAY, 'label' =>  self::TYPE_WORKING_DAY),
                array('value' => self::TYPE_NONWORKING_DAY, 'label' =>  self::TYPE_NONWORKING_DAY),
            );
        }
    }
    public function scopeTypeIn($query, $types = [self::TYPE_PUBLIC_HOLIDAY])
    {
        return $query->whereIn('type', $types);
    }
}
