<?php

namespace App\Models\Repositories;

use App\Models\Entities\ResourceType;
use App\Models\Entities\ticketcomment;
use DB;
use Illuminate\Support\Facades\Auth;

class TicketCommentRepository extends BaseRepository
{
    public function model() {  return 'App\Models\Entities\ticketcomment';   }

    public function latestcscomments($request)
    {
        $ticket_no = $request->ticket_no;
      
        $query = $this->model->latest('updated_at')
            ->where('ticket_no', $ticket_no);


        $comment = $query->first();

        return $comment;
    }

}