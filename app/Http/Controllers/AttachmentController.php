<?php

namespace App\Http\Controllers;

use App\Models\Services\AttachmentService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AttachmentController extends Controller
{
    protected $attachmentService;
    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function getAttachments(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all the attachments
            $attachments= $this->attachmentService->getAttachments($request);
            return response()->json(compact('attachments'));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function downLoadAttachment(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get all the attachments
            $pathToFile= $this->attachmentService->downLoadAttachment($request);
            $pieces = explode('/', $pathToFile);
            if (count($pieces) > 0)
                $fileName = $pieces[count($pieces)-1];
            else
                $fileName='';
//            $pathToFile = str_replace('\\', '', $pathToFile);
//            $pathToFile = str_replace(" ", "\\ ", $pathToFile);
            return response()->download($pathToFile, $fileName);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
