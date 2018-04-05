<?php

namespace App\Models\Entities;


class SystemSetting extends BaseModel
{
    const LAST_IMPORTED_TIME = 'last_imported_time'; //deprecated Not used any more
    const SCHEDULER_EMAIL_ADDRESS = 'scheduler_email_address';
    const ACCOUNTANT_EMAIL_ADDRESS = 'accountant_email_address';


    protected $table = 'system_settings';
    protected $with=[];
    protected $hidden = [
        'active', 'created_at', 'created_by', 'updated_at', 'updated_by'
    ];
    /**
     * @deprecated Not used any more
     * @param $type
     * @return mixed|null
     */
    public static function getSettings($type)
    {
        $setting = SystemSetting::where('type', $type)->where('active', 1)->first();
        if ($setting instanceof SystemSetting)
        {
            $value = $setting->value;
            return $value;
        }
        return null;
    }

    /**
     * @deprecated Not used any more
     * @param $type
     * @param $value
     * @return SystemSetting
     */
    public static function addSettings($type, $value)
    {
        $setting = SystemSetting::where('type', $type)->where('active', 1)->first();
        if ($setting instanceof SystemSetting)
        {
            $setting->value = $value;
            $setting->save();
            return $setting;
        }
        else
        {
            $setting =  new SystemSetting();
            $setting->type = $type;
            $setting->value = $value;
            $setting->active = 1;
            $setting->save();
            return $setting;
        }
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id')->where('active', 1);
    }
}