<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\tickettype1;
use DB;
use Illuminate\Support\Facades\Auth;

class TicketType1Repository extends BaseRepository
{
    public function model() {  return 'App\Models\Entities\tickettype1';   }
  //  public function getTicketType1Table()
  //  {
        //return $this->model->all()->keyBy('id')->toArray();
    //    return $this->model->where('active',1)->get()->keyBy('id')->toArray();
        //return $this->model->where('active',1)->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
   // }

    public function getTicketType1Table($request)
    {
       // $ticketcs->QUOTE_ID = $request->input('QUOTE_ID'); 
        $qi = $request->input('ticket_no'); 
       // return $this->model->all()->toArray();

      // $q1=33;
       return $this->model->where('active',1)->where('ticket_no', $qi)->get()->keyBy('id')->toArray();
      //return $this->model->orderBy('id', 'desc')->first()->toArray();

       // return $this->model->where('active',1)->where('QUOTE_ID',25850)->get()->keyBy('id')->toArray();
       // return $this->model->where('active',1)->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
    }

    public function addTicketType1Table($request)
    {   $tickettype1 =  new tickettype1(); 
        $tickettype1->ticket_no = $request->input('ticket_no');     
        $tickettype1->price = $request->input('price');  
        $tickettype1->description = $request->input('changes');  
        $tickettype1->comment = $request->input('comment');   
        $tickettype1->save();
        return $tickettype1;
    }


    public function updateTicketType1Table($request)
    {
        $ttt =  $this->model->findOrFail($request->input('id'));  
        $ttt->ticket_type = $request->input('ticket_type');
        $ttt->sla_time = $request->input('sla_time');
       $ttt->comment = $request->input('comment');  $ttt->save();  return $ttt;
    }


    public function deleteTicketType1Table($request)
    {   $testb1 =  $this->model->findOrFail($request->input('id'));
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