<?php

namespace App\Models\Entities;


class NoteType extends BaseModel
{
    protected $table = 'note_types';

    // type ID ** IMPORTANT **
    // ** customer note id must be 1
    const TYPE_ID_CUSTOMER = 1;

}