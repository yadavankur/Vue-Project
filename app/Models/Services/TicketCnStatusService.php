<?php

namespace App\Models\Services;

use App\Models\Repositories\TicketCnStatusRepository;

class TicketCnStatusService extends BaseService
{
    protected $TicketCnStatusRepository;
    
        public function __construct(TicketCnStatusRepository $TicketCnStatusRepository) {  $this->TicketCnStatusRepository = $TicketCnStatusRepository;  }
        public function getTicketCnStatusTable()  {   return $this->TicketCnStatusRepository->getTicketCnStatusTable(); }
        public function addTicketCnStatusTable($request)   {  return $this->TicketCnStatusRepository->addTicketCnStatusTable($request); }
       // public function deleteTicketCnStatusTable($request)   {  return $this->TicketCnStatusRepository->deleteTicketCnStatusTable($request);  }

        public function deleteTicketCnStatusTable($request)   {  return $this->TicketCnStatusRepository->deleteTicketCnStatusTable($request);  }
        public function updateTicketCnStatusTable($request)   {  return $this->TicketCnStatusRepository->updateTicketCnStatusTable($request);   }
}