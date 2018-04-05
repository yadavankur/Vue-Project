<?php

namespace App\Models\Entities;


class TmpCpmOrderMatrix extends BaseModel
{
    protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at'];
    protected $table = 'tmp_cpm_order_matrixes';
}