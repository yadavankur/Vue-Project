<?php

namespace App\Models\Entities;

use Illuminate\Support\Facades\Auth;
trait RecordSignature {

    /**
     * Get the creator
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $user = Auth::user();
            if ($user instanceof User)
                $userId = $user->id;
            else
                $userId = env('APP_SYSTEM_USER_ID');
            $model->created_by = $userId;
            $model->updated_by = $userId;
        });
        static::updating(function($model)
        {
            $user = Auth::user();
            if ($user instanceof User)
                $userId = $user->id;
            else
                $userId = env('APP_SYSTEM_USER_ID');
            $model->updated_by = $userId;
        });
    }
}