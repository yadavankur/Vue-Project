<?php

namespace App\Models\Entities;


class TaskMapping extends BaseModel
{

    // with the task name
    // maybe change to const
    // need to match the task_name in the table
    // TODO
    const TASK_BOOKING = 'Booking';
    const TASK_CONFIRMATION = 'Confirmation';
    const TASK_FULL_PAYMENT_CONFIRMATION = 'FullPaymentConfirmation';

    protected $table = 'task_mappings';
    protected $with=[];
    public function activity()
    {
        return $this->belongsTo(CPM_Activity::class, 'activity_id', 'id')
            ->where('active', 1)
            ->with('service_group_activity')
            ->with('service');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id')->where('active', 1);
    }
}