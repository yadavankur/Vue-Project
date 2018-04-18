<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\tickettype2;
use DB;
use Illuminate\Support\Facades\Auth;

class TicketType2ARepository extends BaseRepository
{
    public function model() {  return 'App\Models\Entities\tickettype2';   }
    public function getTicketType1Table($request)
    {
       // $ticketcs->QUOTE_ID = $request->input('QUOTE_ID'); 
        $qi = $request->input('ticket_no'); 
       // return $this->model->all()->toArray();

      // $q1=33;
       return $this->model->where('active',1)->where('ticket_no', $qi)->get()->keyBy('id')->toArray();
      
    }

    public function addTicketType2ATable($request)
    {   $tickettype1 =  new tickettype2(); 
        $tickettype1->ticket_no = $request->input('ticket_no');     
        //$tickettype1->price = $request->input('price');  
       // $tickettype1->description = $request->input('changes');  
        $tickettype1->comment = $request->input('comment');   
        $tickettype1->save();
        return $tickettype1;
    }


    public function updateTicketType2ATable($request)
    {
        $ttt =  $this->model->findOrFail($request->input('id'));  
        $ttt->price = $request->input('price');
        $ttt->ticket_no = $request->input('ticket_no');
        $ttt->description = $request->input('description');
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