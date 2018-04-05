<?php

namespace App\Models\Services;


use App\Models\Repositories\AttachmentRepository;
use Illuminate\Http\Request;

class AttachmentService extends BaseService
{
    protected $attachmentRepository;
    public function __construct(AttachmentRepository $attachmentRepository)
    {
        $this->attachmentRepository = $attachmentRepository;
    }
    public function getAttachments(Request $request)
    {
        return $this->attachmentRepository->getAttachments($request);
    }
    public function downLoadAttachment(Request $request)
    {
        return $this->attachmentRepository->downLoadAttachment($request);
    }
}