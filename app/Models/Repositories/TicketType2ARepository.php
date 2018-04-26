<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\tickettype2;
use DB;
use Illuminate\Support\Facades\Auth;

class TicketType2ARepository extends BaseRepository
{
    public function model() {  return 'App\Models\Entities\tickettype2';   }
    public function getTicketType1Table($request)  //not used
    {  
        $qi = $request->input('ticket_no'); 
       return $this->model->where('active',1)->where('ticket_no', $qi)->get()->keyBy('id')->toArray();
      
    }

    public function addTicketType2ATable($request)
    {   $tickettype1 =  new tickettype2(); 
        $tickettype1->ticket_no = $request->input('ticket_no');     
        $tickettype1->amount = $request->input('price');  
        $tickettype1->aa = $request->input('status_id');  
        $tickettype1->comment = $request->input('comment');  
        $tickettype1->aaa = $request->input('reason');  
        $tickettype1->bb = $request->input('user.id'); //managed user id
        $tickettype1->cc = $request->input('group.id');   //managed user group
        $tickettype1->save();
        return $tickettype1;
    }


    public function updateTicketType2ATable($request)
    {
        $ttt =  $this->model->findOrFail($request->input('id'));  
     //   $ttt->price = $request->input('price');
     $ttt->ticket_no = $request->input('ticket_no');     
     $ttt->amount = $request->input('price');  
     $ttt->aa = $request->input('status_id');  
     $ttt->comment = $request->input('comment');  
     $ttt->aaa = $request->input('reason');  
     $ttt->bb = $request->input('user.id'); //managed user id
     $ttt->cc = $request->input('group.id');   //managed user group
       $ttt->comment = $request->input('comment');  $ttt->save();  return $ttt;
    }


    public function deleteTicketType2ATable($request)
    {   $testb1 =  $this->model->findOrFail($request->input('id'));
        $testb1->active = 0;   
        $testb1->save();   
        return $testb1;
    }

    public function deleteTicketType2AperTicket($request)
    {   $testb1 =  $this->model->findOrFail($request->input('ticket_no'));
        $testb1->active = 0;   
        $testb1->save();   
        return $testb1;
    }


    public function getByPaginate($request)
    {
        //$qi = $request->input('QUOTE_ID'); 

        $sort = $request->sort;                 $sort = explode('|', $sort);
        $sortBy = $sort[0];                     $sortDirection = $sort[1];
        $perPage = $request->per_page;          $search = $request->filter;

        
        $query = $this->model->orderBy($sortBy, $sortDirection) 
        ->where('active',1);

  
        return $query->paginate($perPage);
       
       // return $this->model->orderBy('id', 'desc')->first()->toArray();
    }

}