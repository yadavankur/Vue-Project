<?php

namespace App\Models\Entities;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Log extends BaseModel
{
    const ACTION_TYPE_USER = 'USER_ACTION';
    const ACTION_TYPE_SYSTEM = 'SYSTEM_ACTION';

    const LOG_LEVEL_NORMAL = 'NORMAL';
    const LOG_LEVEL_ERROR = 'ERROR';
    const LOG_LEVEL_WARNING = 'WARNING';

    protected $table = "logs";

    /**
     * mass assignment
     * @var array
     */
    protected $fillable = ['type', 'message', 'entity_name', 'entity_id', 'function_name', 'level'];

    public static function LogEntity($entity, $msg,  $funcName, $level = Log::LOG_LEVEL_NORMAL)
    {
        $user = Auth::user();
        if ($user instanceof User)
        {
            $type = Log::ACTION_TYPE_USER;
        }
        else
        {
            $type = Log::ACTION_TYPE_SYSTEM;
        }
        if (is_array($entity))
        {
            foreach ($entity as $item)
            {
                Log::create([
                    'type' => $type,
                    'message' => $msg,
                    'entity_name' => $item? get_class($item): get_called_class(),
                    'entity_id' => $item? $item->id : '',
                    'function_name' => $funcName,
                    'level' => $level
                ]);
            }
        }
        else
        {
            Log::create([
                'type' => $type,
                'message' => $msg,
                'entity_name' => $entity? get_class($entity): get_called_class(),
                'entity_id' => $entity? $entity->id : '',
                'function_name' => $funcName,
                'level' => $level
            ]);
        }
    }
}