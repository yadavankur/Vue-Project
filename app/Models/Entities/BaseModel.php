<?php
namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class BaseModel extends Model
{   const OWNER_TYPE_USER = 'USER';// owner type: user or manager
    const OWNER_TYPE_MANAGER = 'MANAGER';
    const USER_TYPE_SINGLE_USER = 'USER';  // user type: user or group
    const USER_TYPE_GROUP = 'GROUP';
    protected $dates = ['created_at', 'updated_at'];
    protected $with=['createdBy', 'updatedBy'];
//    protected $hidden = [
//        'created_at', 'created_by', 'updated_by', 'updated_at', 'active'
//    ];
    /*** Get the creator     */
    public function createdBy()
    { return $this->belongsTo(User::class, 'created_by', 'id');   }
    /** * Get the updater */
    public function updatedBy()
    {  return $this->belongsTo(User::class, 'updated_by', 'id');    }
    /**
     * listen for creating and updating events
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $user = Auth::user();
            if ($user instanceof User)   $userId = $user->id;
            else    $userId = env('APP_SYSTEM_USER_ID');
            $model->created_by = $userId;
            $model->updated_by = $userId;
        });
        static::updating(function($model)
        {   $user = Auth::user();
            if ($user instanceof User)        $userId = $user->id;
            else  $userId = env('APP_SYSTEM_USER_ID');
            $model->updated_by = $userId;
        });
    }
    /**
     * Scope a query to only include active models.
     * @param $query
     * @param int $active
     * @return mixed
     */
    public function scopeActive($query, $active = 1)
    {   return $query->where('active', $active);   }
}