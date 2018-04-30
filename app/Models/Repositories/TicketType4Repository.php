<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\tickettype4;
use DB;
use Illuminate\Support\Facades\Auth;

class TicketType4Repository extends BaseRepository
{
    public function model() {  return 'App\Models\Entities\tickettype4';   }
    public function getTicketType3Table($request) //not used
    {
        $qi = $request->input('ticket_no'); 
        return $this->model->where('active',1)->where('ticket_no', $qi)->get()->keyBy('id')->toArray();
      
    }

    public function addTicketType3Table($request)
    {   $tickettype1 =  new tickettype4(); 
        $tickettype1->ticket_no = $request->input('ticket_no');     
        $tickettype1->amount = $request->input('price');  
        $tickettype1->aa = $request->input('status_id');  
        $tickettype1->comment = $request->input('comment');  
        $tickettype1->aaa = $request->input('reason');  
        $tickettype1->bb = $request->input('user.id'); //managed user id
        $tickettype1->cc = $request->input('group.id');   //managed user group
        if($request->input('builderorcustomer')) $tickettype1->builderorcustomer = $request->input('builderorcustomer');
        if($request->input('factory')) $tickettype1->factory = $request->input('factory'); 
        if($request->input('service')) $tickettype1->service = $request->input('service'); 
        if($request->input('customerservice')) $tickettype1->customerservice = $request->input('customerservice'); 
        if($request->input('sales')) $tickettype1->sales = $request->input('sales'); 
        if($request->input('estimating')) $tickettype1->estimating = $request->input('estimating'); 
        if($request->input('transport')) $tickettype1->transport = $request->input('transport'); 
        if($request->input('supplier')) $tickettype1->supplier = $request->input('supplier'); 
        if($request->input('other')) $tickettype1->other = $request->input('other'); 

        $tickettype1->issues = $request->input('issues'); 
        $tickettype1->officeuse = $request->input('officeuse'); 
      
        $tickettype1->save();
        return $tickettype1;
    }


    public function updateTicketType3Table($request)
    {
     $ttt =  $this->model->findOrFail($request->input('id'));  
     $ttt->ticket_no = $request->input('ticket_no');     
     $ttt->aa = $request->input('status_id');  
     $ttt->comment = $request->input('comment');  
     $ttt->aaa = $request->input('reason');  
     $ttt->bb = $request->input('user.id'); //managed user id
     $ttt->cc = $request->input('group.id');   //managed user group
     $ttt->comment = $request->input('comment');  
     $ttt->builderorcustomer = $request->input('builderorcustomer');
     $ttt->factory = $request->input('factory'); 
     $ttt->service = $request->input('service'); 
     $ttt->customerservice = $request->input('customerservice'); 
     $ttt->sales = $request->input('sales'); 
     $ttt->estimating = $request->input('estimating'); 
     $ttt->transport = $request->input('transport'); 
     $ttt->supplier = $request->input('supplier'); 
     $ttt->other = $request->input('other'); 
     $ttt->issues = $request->input('issues'); 
     $ttt->officeuse = $request->input('officeuse'); 
       
       $ttt->save();  return $ttt;
    }


    public function deleteTicketType3Table($request)
    {   $testb1 =  $this->model->findOrFail($request->input('id'));
        $testb1->active = 0;   
        $testb1->save();   
        return $testb1;
    }

    public function deleteTicketType3perTicket($request)
    {   $testb1 =  $this->model->findOrFail($request->input('ticket_no'));
        $testb1->active = 0;   
        $testb1->save();   
        return $testb1;
    }


    public function getByPaginate($request)
    {
        $sort = $request->sort;                 $sort = explode('|', $sort);
        $sortBy = $sort[0];                     $sortDirection = $sort[1];
        $perPage = $request->per_page;          $search = $request->filter;
        
        $query = $this->model->orderBy($sortBy, $sortDirection) 
        ->where('active',1);
  
        return $query->paginate($perPage);
     
    }

}