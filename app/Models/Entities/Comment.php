<?php

namespace App\Models\Entities;


class Comment extends BaseModel
{
    protected $table = 'comments';

    // type ID
    const TYPE_ID_CUSTOMER = 1;

    // type of notes
    const TYPE_CUSTOMER = 'CUSTOMER_NOTES';
    const TYPE_DOWELL = 'DOWELL_NOTES';

    public function note_type()
    {
        return $this->belongsTo(NoteType::class, 'note_type_id', 'id')->where('active', 1);
    }


}