<?php

namespace App\Models\Services;

use App\Models\Repositories\TicketCsRepository;

class TicketCsService extends BaseService
{
    protected $ticketCsRepository;
    
        public function __construct(TicketCsRepository $ticketCsRepository) {  $this->ticketCsRepository = $ticketCsRepository;  }
   
        public function addcsticket($request)   {  return $this->ticketCsRepository->add($request); }
        public function editcsticket($request)   {  return $this->ticketCsRepository->save($request); }
        public function displaycs($request)   {  return $this->ticketCsRepository->displaycs($request); }
       // public function displaycs()   {  return $this->ticketCsRepository->displaycs(); }
       public function getlastcsticket($request){  return $this->ticketCsRepository->getlastcsticket($request); }
//-------------get pagination---------------
        public function getByPaginate( $request)
            {   return $this->ticketCsRepository->getByPaginate($request);}
        public function deleteCsTicket($request)   
            {  return $this->ticketCsRepository->deleteCsTicket($request);  }

        public function  addfile($request)   {  return $this->ticketCsRepository->addfile($request); }

        public function gettype1ticket( $request)
        {   return $this->ticketCsRepository->gettype1ticket($request);}
        public function gettype2Aticket( $request)
        {   return $this->ticketCsRepository->gettype2Aticket($request);}
        public function gettype3ticket( $request)
        {   return $this->ticketCsRepository->gettype3ticket($request);}
        public function gettype4ticket( $request)
        {   return $this->ticketCsRepository->gettype4ticket($request);}


}