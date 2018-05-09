<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\tickettype5;
use DB;
use Illuminate\Support\Facades\Auth;

class TicketType5Repository extends BaseRepository
{
    public function model() {  return 'App\Models\Entities\tickettype5';   }
    public function getTicketType5Table($request) //not used
    {
        $qi = $request->input('ticket_no'); 
        return $this->model->where('active',1)->where('ticket_no', $qi)->get()->keyBy('id')->toArray();
      
    }

    public function addTicketType5Table($request)
    {   $tickettype1 =  new tickettype5(); 
        $tickettype1->ticket_no = $request->input('ticket_no');     
     
        $tickettype1->astatus = $request->input('status_id');  
        $tickettype1->comment = $request->input('comment');  
        $tickettype1->aaa = $request->input('allitems2'); //all items
        $tickettype1->bbb = $request->input('allerrors2');
        $tickettype1->ccc = $request->input('allnotes2');
        $tickettype1->auser = $request->input('user.id'); //managed user id
        $tickettype1->agroup = $request->input('group.id');   //managed user group

        $tickettype1->save();
        return $tickettype1;
    }


    public function updateTicketType5Table($request)
    {
     $tickettype1 =  $this->model->findOrFail($request->input('id'));  
     $tickettype1->ticket_no = $request->input('ticket_no');     
     $tickettype1->astatus = $request->input('status_id');  
     $tickettype1->comment = $request->input('comment');  
     $tickettype1->aaa = $request->input('allitems2'); //all items
     $tickettype1->bbb = $request->input('allerrors2');
     $tickettype1->ccc = $request->input('allnotes2');
     $tickettype1->auser = $request->input('user.id'); //managed user id
     $tickettype1->agroup = $request->input('group.id');   //managed user group
       
       $tickettype1->save();  return $tickettype1;
    }


    public function deleteTicketType5Table($request)
    {   $testb1 =  $this->model->findOrFail($request->input('id'));
        $testb1->active = 0;   
        $testb1->save();   
        return $testb1;
    }

    public function deleteTicketType5perTicket($request)
    {   $testb1 =  $this->model->findOrFail($request->input('ticket_no'));
        $testb1->active = 0;   
        $testb1->save();   
        return $testb1;
    }



}