<?php

namespace App\Models\Entities;

class ResourceType extends BaseModel
{
    const ALL= 0;
    const LOCATION = 1;
    const MENU = 2;
    const TAB = 3;
    const PAGE = 4;
    const COMPONENT = 5;
    const PROCESS = 6;
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resource_types';

}
