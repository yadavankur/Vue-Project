<?php

namespace App\Models\Services;


use App\Models\Repositories\CommentRepository;
use Illuminate\Http\Request;

class CommentService extends BaseService
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }
    public function saveNotes(Request $request, $type) {

        return $this->commentRepository->saveNotes($request, $type);
    }
    public function getByPaginate(Request $request, $type)
    {
        return $this->commentRepository->getByPaginate($request, $type);
    }
    public function getLatestNotes(Request $request)
    {
        return $this->commentRepository->getLatestNotes($request);
    }
}