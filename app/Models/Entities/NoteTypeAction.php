<?php

namespace App\Models\Entities;


class NoteTypeAction extends BaseModel
{
    protected $table = 'note_type_actions';

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id')->where('active', 1);
    }
    public function note_type()
    {
        return $this->belongsTo(NoteType::class, 'note_type_id', 'id')->where('active', 1);
    }
}