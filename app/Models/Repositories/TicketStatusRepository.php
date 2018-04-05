<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\ticketstatus;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Entities\testb1;

class TicketStatusRepository extends BaseRepository
{
    public function model() {  return 'App\Models\Entities\ticketstatus';   }
   // public function model1() {  return 'App\Models\Entities\testb1';   }
    public function getTicketStatusTable()
    {
        //return $this->model->all()->keyBy('id')->toArray();
        return $this->model->where('active',1)->get()->keyBy('id')->toArray();
        //return $this->model->where('active',1)->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
    }

    public function addTicketStatusTable($request)
    {   $ticketstatus =  new ticketstatus();    
        $ticketstatus->STATUS = $request->input('STATUS');  
        $ticketstatus->comment = $request->input('comment');   
        $ticketstatus->save();
        return $ticketstatus;
    }


    public function updateTicketStatusTable($request)
    {
        $ttt =  $this->model->findOrFail($request->input('id'));  $ttt->STATUS = $request->input('STATUS');
       $ttt->comment = $request->input('comment');  $ttt->save();  return $ttt;
    }



    public function deleteTicketStatusTable($request)
    {   $testb1 =  $this->model->findOrFail($request->input('id'));
        $testb1->active = 0;   
        $testb1->save();   
        return $testb1;
    }
}