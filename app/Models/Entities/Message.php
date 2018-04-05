<?php

namespace App\Models\Entities;

class Message extends BaseModel
{
    const TYPE_OVERDUE = 'OVERDUE';
    const TYPE_DUETODAY = 'DUETODAY';
    const TYPE_DUETOMORROW = 'DUETOMORROW';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['read_at', 'created_at', 'updated_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'message', 'to_user_id'];

    public function to()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
    /**
     * Scoping the unread messages
     */
    public function scopeUnRead($query)
    {
        return $query->whereNull('read_at')
            ->whereNull('read_by_id');
    }

    /**
     * Scoping the read messages
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at')
            ->whereNotNull('read_by_id');
    }

    /**
     * Scoping the user
     *
     * @param $query
     * @param User $user
     * @return mixed
     */
    public function scopeUser($query, User $user)
    {
        return $query->where('to_user_id', $user->id);
    }

    /**
     * Scoping the sender
     * @param $query
     * @param User $user
     */
    public function scopeSender($query, User $user)
    {
        return $query->where('created_by', $user->id);
    }

}

