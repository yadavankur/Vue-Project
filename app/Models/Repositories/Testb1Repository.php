<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\testb1;
use DB;
use Illuminate\Support\Facades\Auth;

class Testb1Repository extends BaseRepository
{
    public function model() {  return 'App\Models\Entities\testb1';   }
    public function getTest1()
    {
        //return $this->model->all()->keyBy('id')->toArray();
        return $this->model->where('active',1)->get()->keyBy('id')->toArray();
        //return $this->model->where('active',1)->get(['*', DB::raw("'RW' as permission")])->keyBy('id')->toArray();
    }
    public function add($request)
    {   $testb1 =  new testb1();    
        $testb1->name = $request->input('name');  
        $testb1->title = $request->input('title');   
        $testb1->save();   
        return $testb1;
    }
    public function delete($request)
    {   $testb1 =  $this->model->findOrFail($request->input('id'));
        $testb1->active = 0;   
        $testb1->save();   
        return $testb1;
    }

    public function update1($request)
    {
        $ttt =  $this->model->findOrFail($request->input('id'));  $ttt->name = $request->input('name');
       $ttt->title = $request->input('title');  $ttt->save();  return $ttt;
    }
}