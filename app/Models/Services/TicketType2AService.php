<?php

namespace App\Models\Services;

use App\Models\Repositories\TicketType2ARepository;

class TicketType2AService extends BaseService
{
    protected $TicketType2ARepository;
    
        public function __construct(TicketType2ARepository $TicketType2ARepository) {  $this->TicketType2ARepository = $TicketType2ARepository;  }
       // public function getTicketType1Table()  {   return $this->TicketType1Repository->getTicketType1Table(); }
        public function getTicketType2ATable($request)   {  return $this->TicketType2ARepository->getTicketType2ATable($request); }
        public function addTicketType2ATable($request)   {  return $this->TicketType2ARepository->addTicketType2ATable($request); }
        public function deleteTicketType2ATable($request) {  return $this->TicketType2ARepository->deleteTicketType2ATable($request);  }
        public function updateTicketType2ATable($request) {  return $this->TicketType2ARepository->updateTicketType2ATable($request);   }
        public function getByPaginate( $request)
        {   return $this->TicketType2ARepository->getByPaginate($request);}
}