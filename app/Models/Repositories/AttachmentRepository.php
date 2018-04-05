<?php

namespace App\Models\Repositories;


use App\Models\Entities\V6Quote;
use App\Models\Entities\V6QuoteDocs;
use App\Models\Utils\UtilsAbstract;
use Exception;
use DB;

class AttachmentRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\V6QuoteDocs';
    }
    public function getAttachments($request)
    {
        $orderNo = $request->orderId;
        $quoteId = $request->quoteId;
        $location = $request->location;
        // get order info from V6Quote view
        $quote = V6Quote::where('QUOTE_ID', $quoteId)->where('QUOTE_NUM_PREF', $location)
            ->where('UDF1', $orderNo)->first();
        if (!$quote instanceof V6Quote)
        {
            // throw exception
            throw new Exception('Invalid order no!');
        }

        // get all associated pdf file
        $quoteDoc = $this->model->where('UDF1',$orderNo)
                ->where('QUOTE_ID', $quote->QUOTE_ID)->where('QUOTE_VERS', $quote->QUOTE_VERS)
                ->first();
        if (!$quoteDoc instanceof V6QuoteDocs)
        {
            // no pdf found
            return array();
        }
        $filePath = $quoteDoc->DoucmentFolder;
        //get all files
        $files = DB::select("SET NOCOUNT ON; EXEC dbo.GET_Subfiles ?;", array($filePath));
        $retFiles = [];
        foreach($files as $file)
        {
            $obj = new \stdClass();
            $pieces = explode('\\', $file->FilePath);
            if (count($pieces) > 0)
                $fileName = $pieces[count($pieces)-1];
            else
                continue;
            $obj->fileName = $fileName;
            $obj->fullPath = $file->FilePath;
            $obj->_checked = false;
            $obj->version = ($file->MaxVersion == 'T'? 'Latest' : 'Old');
            $retFiles[] = $obj;
        }
        return $retFiles;
    }
    public function downLoadAttachment($request)
    {
        //$orderNo = $request->orderId;
        $fileName = $request->fileName;
//        // replace v: with /home/V6_3_Quotes/
//        $search = '\\';
//        $replace = '/';
//        $fileName = str_replace($search, $replace, $fileName);
//        $storagePath = env('APP_STORAGE_QUOTES');
//        $fileNameWithPath = str_replace('v:', $storagePath, $fileName);
        $fileNameWithPath = UtilsAbstract::getRealPath($fileName);
        return $fileNameWithPath;
    }
}