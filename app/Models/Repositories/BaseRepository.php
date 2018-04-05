<?php

namespace App\Models\Repositories;


use App\Models\Entities\Log;
use App\Models\Entities\OrderActivityLog;
use Bosnadev\Repositories\Eloquent\Repository;

abstract class BaseRepository extends Repository
{

    public static function isAdmin($user)
    {
        $isAdmin = false;
        //$adminUserIds = [1,2,3]; // need to tweak 1,2,3
        $roles = $user->roles();
        foreach($roles as $role)
        {
            //if (in_array($role->id, $adminUserIds))
            if ($role->is_root || $role->is_admin)
            {
                $isAdmin = true;
                break;
            }
        }
        return $isAdmin;
    }
    public static function isRoot($user)
    {
        $isRoot = false;
        //$adminUserIds = [1,2,3]; // need to tweak 1,2,3
        $roles = $user->roles();
        foreach($roles as $role)
        {
            //if (in_array($role->id, $adminUserIds))
            if ($role->is_root)
            {
                $isRoot = true;
                break;
            }
        }
        return $isRoot;
    }

    public static function AddLog($quote_id, $location_id, $order_id, $activity, $comment, $action_detail = '')
    {
        OrderActivityLog::create([
            'quote_id' => $quote_id,
            'location_id' => $location_id,
            'order_id' => $order_id,
            'activity' => $activity,
            'comment' => $comment,
            'action_detail' => $action_detail,
        ]);
    }
    public static function LogEntity($entity, $msg,  $funcName, $level = Log::LOG_LEVEL_NORMAL)
    {
        Log::LogEntity($entity, $msg,  $funcName, $level);
    }
}