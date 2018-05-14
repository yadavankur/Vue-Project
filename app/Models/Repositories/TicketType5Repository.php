<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\tickettype5;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Services\EmailService;
use App\Models\Entities\Email;
use App\Models\Entities\Location;
use App\Models\Entities\V6Quote;
use App\Models\Utils\UtilsAbstract;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\SMS\Facades\SMS;

class TicketType5Repository extends BaseRepository
{
    public function model() {  return 'App\Models\Entities\tickettype5';   }
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

        $tickettype1->save();

        //Mail::send('emails.welcome', ['name' => 'Novice'], function($message){
         //   $message->to('manoj.mishra@dowell.com.au', 'Fabien')->subject('Bienvenue !');
       // });
 Mail::raw('Text to e-mail', function($message)
{
   // $message->from('us@example.com', 'Laravel');

    $message->to('manoj.mishra@dowell.com.au')->cc('manojmishra571980@gmail.com');
});
      //  Mail::send(new $class($content, $order));
        return $tickettype1;
    }


    public function updateTicketType5Table($request)
    {
     $tickettype1 =  $this->model->findOrFail($request->input('id'));  
     $tickettype1->ticket_no = $request->input('ticket_no');     
     $tickettype1->astatus = $request->input('status_id');  
     $tickettype1->comment = $request->input('comment');  
     $tickettype1->aaa = $request->input('allitems2'); //all items
     $tickettype1->bbb = $request->input('allerrors2');
     $tickettype1->ccc = $request->input('allnotes2');
     $tickettype1->auser = $request->input('user.id'); //managed user id
     $tickettype1->agroup = $request->input('group.id');   //managed user group
       
       $tickettype1->save();  return $tickettype1;
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