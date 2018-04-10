<?php

namespace App\Models\Services;

use App\Models\Repositories\TicketCommentRepository;

class TicketCommentService extends BaseService
{
    protected $TicketCommentRepository;
    
        public function __construct(TicketCommentRepository $TicketCommentRepository) {  $this->TicketCommentRepository = $TicketCommentRepository;  }
       // public function getTicketType1Table()  {   return $this->TicketType1Repository->getTicketType1Table(); }
        public function latestcscomments($request)   {  return $this->TicketCommentRepository->latestcscomments($request); }
      //  public function addTicketType1Table($request)   {  return $this->TicketCommentRepository->addTicketType1Table($request); }
      //  public function deleteTicketType1Table($request) {  return $this->TicketCommentRepository->deleteTicketType1Table($request);  }
      //  public function updateTicketType1Table($request) {  return $this->TicketCommentRepository->updateTicketType1Table($request);   }
      //  public function getByPaginate( $request)  {   return $this->TicketCommentRepository->getByPaginate($request);}
}