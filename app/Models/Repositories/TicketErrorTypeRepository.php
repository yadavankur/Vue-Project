<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\ticketerrortype;
use DB;
use Illuminate\Support\Facades\Auth;


class TicketErrorTypeRepository extends BaseRepository
{
    public function model() {  return 'App\Models\Entities\ticketerrortype';   }
    public function getTicketErrorTypeTable()
    {
      
        return $this->model->where('active',1)->with('ttype')->get()->keyBy('id')->toArray();
       
    }

    public function addTicketErrorTypeTable($request)
    {   $ticketerrortype =  new ticketerrortype();    
        $ticketerrortype->errorcode = $request->input('STATUS');  
        $ticketerrortype->comment = $request->input('comment');   
        $ticketerrortype->ticket_type_id = $request->input('ticket_type_id');   
        $ticketerrortype->save(); 
        return $ticketerrortype;
    }


    public function updateTicketErrorTypeTable($request)
    {
        $ttt =  $this->model->findOrFail($request->input('id')); 
        $ttt->errorcode = $request->input('STATUS');  
        $ttt->comment = $request->input('comment');   
        $ttt->ticket_type_id = $request->input('ticket_type_id'); 
        $ttt->save();  return $ttt;
    }



    public function deleteTicketErrorTypeTable($request)
    {   $testb1 =  $this->model->findOrFail($request->input('id'));
        $testb1->active = 0;   
        $testb1->save();   
        return $testb1;
    }
}