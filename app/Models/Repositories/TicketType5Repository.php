<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\tickettype5;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\SMS\Facades\SMS;
use App\Models\Entities\ticket_cs;

class TicketType5Repository extends BaseRepository
{
    public function model() {  return 'App\Models\Entities\tickettype5';   }
   // public function modelcs() {  return 'App\Models\Entities\Ticket_cs';   }
    
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
       
       
     //   $ticket_cs1 =  ticket_cs::findOrFail($request->input('ticket_no'));

        $tickettype1->save();
        //----------------
         
       // $ticket_cs1->save();
        //----------------


        //Mail::send('emails.welcome', ['name' => 'Novice'], function($message){
         //   $message->to('manoj.mishra@dowell.com.au', 'Fabien')->subject('Bienvenue !');
       // });
 //Mail::raw("An SDA in Ticket No. $tickettype1->ticket_no has been added for your approval ", function($message)
  //  {   // $message->from('us@example.com', 'Laravel');
  //   $message->to('mark.greenwood@dowell.com.au')->cc('manoj.mishra@dowell.com.au');
  //  });
  
Mail::raw([], function($message) {
    $message->from('contact@company.com', 'Company name');
    $message->to('manoj.mishra@dowell.com.au');
    $message->subject('5% off all our website');
    $message->setBody( '<html><h1>5% off its awesome</h1><p>Go get it now !</p></html>', 'text/html' );
    $message->addPart("5% off its awesome\n\nGo get it now!", 'text/plain');
});

      //  Mail::send(new $class($content, $order));
        return $tickettype1;
    }


    public function updateTicketType5Table($request)
    {
     $tickettype1 =  $this->model->findOrFail($request->input('id'));  
     $ticketcs =  ticket_cs::findOrFail($request->input('ticket_no'));  
     $tickettype1->ticket_no = $request->input('ticket_no');     
     $tickettype1->astatus = $request->input('status_id');  
     $tickettype1->comment = $request->input('comment');  
     $tickettype1->aaa = $request->input('allitems2'); //all items
     $tickettype1->bbb = $request->input('allerrors2');
     $tickettype1->ccc = $request->input('allnotes2');
     $tickettype1->officeuse = $ticketcs;
     $tickettype1->auser = $request->input('user.id'); //managed user id
     $tickettype1->agroup = $request->input('group.id');   //managed user group
       
     $tickettype1->save(); // $ticketcs->save(); 
     return $tickettype1;
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