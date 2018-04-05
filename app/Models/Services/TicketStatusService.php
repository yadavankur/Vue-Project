<?php

namespace App\Models\Services;

use App\Models\Repositories\TicketStatusRepository;

class TicketStatusService extends BaseService
{
    protected $TicketStatusRepository;
    
        public function __construct(TicketStatusRepository $TicketStatusRepository) {  $this->TicketStatusRepository = $TicketStatusRepository;  }
        public function getTicketStatusTable()  {   return $this->TicketStatusRepository->getTicketStatusTable(); }
        public function addTicketStatusTable($request)   {  return $this->TicketStatusRepository->addTicketStatusTable($request); }
       // public function deleteTicketStatusTable($request)   {  return $this->TicketStatusRepository->deleteTicketStatusTable($request);  }

        public function deleteTicketStatusTable($request)   {  return $this->TicketStatusRepository->deleteTicketStatusTable($request);  }
        public function updateTicketStatusTable($request)   {  return $this->TicketStatusRepository->updateTicketStatusTable($request);   }
}