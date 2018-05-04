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
      
        return $this->model->where('active',1)->get()->keyBy('id')->toArray();
       
    }

    public function addTicketErrorTypeTable($request)
    {   $TicketCnStatus =  new TicketCnStatus();    
        $TicketCnStatus->STATUS = $request->input('STATUS');  
        $TicketCnStatus->comment = $request->input('comment');   
        $TicketCnStatus->save();
        return $TicketCnStatus;
    }


    public function updateTicketErrorTypeTable($request)
    {
        $ttt =  $this->model->findOrFail($request->input('id'));  $ttt->STATUS = $request->input('STATUS');
       $ttt->comment = $request->input('comment');  $ttt->save();  return $ttt;
    }



    public function deleteTicketErrorTypeTable($request)
    {   $testb1 =  $this->model->findOrFail($request->input('id'));
        $testb1->active = 0;   
        $testb1->save();   
        return $testb1;
    }
}