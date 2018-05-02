<?php

namespace App\Models\Services;

use App\Models\Repositories\TicketType4Repository;

class TicketType4Service extends BaseService
{
    protected $TicketType4Repository;
    
        public function __construct(TicketType4Repository $TicketType4Repository) {  $this->TicketType4Repository = $TicketType4Repository;  }
       // public function getTicketType1Table()  {   return $this->TicketType1Repository->getTicketType1Table(); }
        public function getTicketType4Table($request)   {  return $this->TicketType4Repository->getTicketType4Table($request); }
        public function addTicketType4Table($request)   {  return $this->TicketType4Repository->addTicketType4Table($request); }
        public function deleteTicketType4Table($request) {  return $this->TicketType4Repository->deleteTicketType4Table($request);  }
        public function updateTicketType4Table($request) {  return $this->TicketType4Repository->updateTicketType4Table($request);   }
        public function getByPaginate( $request)
        {   return $this->TicketType4Repository->getByPaginate($request);}
}