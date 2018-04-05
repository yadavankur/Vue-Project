<?php

namespace App\Models\Services;


use App\Models\Repositories\CpmCommentRepository;
use Illuminate\Http\Request;

class CpmCommentService extends BaseService
{
    protected $cpmCommentRepository;

    public function __construct(CpmCommentRepository $cpmCommentRepository) {
        $this->cpmCommentRepository = $cpmCommentRepository;
    }
    public function saveNotes(Request $request, $type) {

        return $this->cpmCommentRepository->saveNotes($request, $type);
    }
    public function getByPaginate(Request $request, $type)
    {
        return $this->cpmCommentRepository->getByPaginate($request, $type);
    }
    public function getLatestNotes(Request $request)
    {
        return $this->cpmCommentRepository->getLatestNotes($request);
    }
}