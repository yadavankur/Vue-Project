<?php

namespace App\Models\Services;

use App\Models\Repositories\TicketType1Repository;

class TicketType1Service extends BaseService
{
    protected $TicketType1Repository;
    
        public function __construct(TicketType1Repository $TicketType1Repository) {  $this->TicketType1Repository = $TicketType1Repository;  }
       // public function getTicketType1Table()  {   return $this->TicketType1Repository->getTicketType1Table(); }
        public function getTicketType1Table($request)   {  return $this->TicketType1Repository->getTicketType1Table($request); }
        public function addTicketType1Table($request)   {  return $this->TicketType1Repository->addTicketType1Table($request); }
        public function deleteTicketType1Table($request) {  return $this->TicketType1Repository->deleteTicketType1Table($request);  }
        public function updateTicketType1Table($request) {  return $this->TicketType1Repository->updateTicketType1Table($request);   }
        public function getByPaginate( $request)
        {   return $this->TicketType1Repository->getByPaginate($request);}
}