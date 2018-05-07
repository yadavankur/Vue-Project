<?php

namespace App\Models\Services;

use App\Models\Repositories\TicketType5Repository;

class TicketType5Service extends BaseService
{
    protected $TicketType5Repository;
    
        public function __construct(TicketType5Repository $TicketType5Repository) {  $this->TicketType5Repository = $TicketType5Repository;  }
       // public function getTicketType1Table()  {   return $this->TicketType1Repository->getTicketType1Table(); }
        public function getTicketType5Table($request)   {  return $this->TicketType5Repository->getTicketType5Table($request); }
        public function addTicketType5Table($request)   {  return $this->TicketType5Repository->addTicketType5Table($request); }
        public function deleteTicketType5Table($request) {  return $this->TicketType5Repository->deleteTicketType5Table($request);  }
        public function updateTicketType5Table($request) {  return $this->TicketType5Repository->updateTicketType5Table($request);   }
        public function getByPaginate( $request)
        {   return $this->TicketType5Repository->getByPaginate($request);}
}