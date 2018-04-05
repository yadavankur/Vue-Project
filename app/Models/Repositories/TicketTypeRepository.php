<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\tickettype;
use DB;
use Illuminate\Support\Facades\Auth;

class TicketTypeRepository extends BaseRepository
{
    public function model() {  return 'App\Models\Entities\tickettype';   }
    public function getTicketTypeTable()
    {
        //return $this->model->all()->keyBy('id')->toArray();
        return $this->model->where('active',1)->get()->keyBy('id')->toArray();
        //return $this->model->where('active',1)->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
    }

    public function addTicketTypeTable($request)
    {   $tickettype =  new tickettype();    
        $tickettype->ticket_type = $request->input('ticket_type');  
        $tickettype->sla_time = $request->input('sla_time');  
        $tickettype->comment = $request->input('comment');   
        $tickettype->save();
        return $tickettype;
    }


    public function updateTicketTypeTable($request)
    {
        $ttt =  $this->model->findOrFail($request->input('id'));  
        $ttt->ticket_type = $request->input('ticket_type');
        $ttt->sla_time = $request->input('sla_time');
       $ttt->comment = $request->input('comment');  $ttt->save();  return $ttt;
    }


    public function deleteTicketTypeTable($request)
    {   $testb1 =  $this->model->findOrFail($request->input('id'));
        $testb1->active = 0;   
        $testb1->save();   
        return $testb1;
    }

}