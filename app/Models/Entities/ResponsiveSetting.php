<?php

namespace App\Models\Entities;

class ResponsiveSetting extends BaseModel
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'responsive_settings';

    public function device_type()
    {
        return $this->belongsTo(DeviceType::class, 'device_type_id')->where('active',1);
    }
    public function component()
    {
        return $this->belongsTo(Component::class, 'component_id')->where('active',1)->with('page')->with('parent');
    }
}
