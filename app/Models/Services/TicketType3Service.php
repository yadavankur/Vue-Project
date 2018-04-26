<?php

namespace App\Models\Services;

use App\Models\Repositories\TicketType3Repository;

class TicketType3Service extends BaseService
{
    protected $TicketType3Repository;
    
        public function __construct(TicketType3Repository $TicketType3Repository) {  $this->TicketType3Repository = $TicketType3Repository;  }
       // public function getTicketType1Table()  {   return $this->TicketType1Repository->getTicketType1Table(); }
        public function getTicketType3Table($request)   {  return $this->TicketType3Repository->getTicketType3Table($request); }
        public function addTicketType3Table($request)   {  return $this->TicketType3Repository->addTicketType3Table($request); }
        public function deleteTicketType3Table($request) {  return $this->TicketType3Repository->deleteTicketType3Table($request);  }
        public function updateTicketType3Table($request) {  return $this->TicketType3Repository->updateTicketType3Table($request);   }
        public function getByPaginate( $request)
        {   return $this->TicketType3Repository->getByPaginate($request);}
}