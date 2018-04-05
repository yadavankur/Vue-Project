<?php

namespace App\Models\Services;


use App\Models\Repositories\MessageRepository;

class MessageService extends BaseService
{
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }
    public function getByPaginate($request)
    {
        return $this->messageRepository->getByPaginate($request);
    }
    public function updateNotificationStatus($request)
    {
        return $this->messageRepository->updateNotificationStatus($request);
    }
    public function getBadgeCount($request)
    {
        return $this->messageRepository->getBadgeCount($request);
    }
}