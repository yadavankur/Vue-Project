<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\ticketcnstatus;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Entities\testb1;

class TicketCnStatusRepository extends BaseRepository
{
    public function model() {  return 'App\Models\Entities\ticketcnstatus';   }
   // public function model1() {  return 'App\Models\Entities\testb1';   }
    public function getTicketCnStatusTable()
    {
        //return $this->model->all()->keyBy('id')->toArray();
        return $this->model->where('active',1)->get()->keyBy('id')->toArray();
        //return $this->model->where('active',1)->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
    }

    public function addTicketCnStatusTable($request)
    {   $TicketCnStatus =  new TicketCnStatus();    
        $TicketCnStatus->STATUS = $request->input('STATUS');  
        $TicketCnStatus->comment = $request->input('comment');   
        $TicketCnStatus->save();
        return $TicketCnStatus;
    }


    public function updateTicketCnStatusTable($request)
    {
        $ttt =  $this->model->findOrFail($request->input('id'));  $ttt->STATUS = $request->input('STATUS');
       $ttt->comment = $request->input('comment');  $ttt->save();  return $ttt;
    }



    public function deleteTicketCnStatusTable($request)
    {   $testb1 =  $this->model->findOrFail($request->input('id'));
        $testb1->active = 0;   
        $testb1->save();   
        return $testb1;
    }
}