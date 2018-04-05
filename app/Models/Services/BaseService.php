<?php

namespace App\Models\Services;


use App\Models\Entities\Log;

Abstract class BaseService
{
    public function LogEntity($entity, $msg,  $funcName, $level = Log::LOG_LEVEL_NORMAL)
    {
        Log::LogEntity($entity, $msg,  $funcName, $level);
    }

}