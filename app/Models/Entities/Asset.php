<?php

namespace App\Models\Entities;


use App\Models\Utils\UtilsAbstract;
use Carbon\Carbon;
use Exception;

class Asset extends BaseModel
{
    const TYPE_TMP = 'TEMP';

    protected $table = 'assets';

    private static function copyToAssetFolder($newFile, $dataOrFile)
    {
        if(!preg_match('#^(\w+/){1,2}\w+\.\w+$#',$dataOrFile) || !is_file($dataOrFile))
            return file_put_contents($newFile, $dataOrFile);
        return rename($dataOrFile, $newFile);
    }
    public static function registerAsset($filename, $dataOrFile, $type = self::TYPE_TMP)
    {
        if(!is_string($dataOrFile) && (!is_file($dataOrFile)))
            throw new Exception(__CLASS__ . '::' . __FUNCTION__ . '() will ONLY take string to save!');

        $assetId = md5($filename . '::' . microtime());
        $path = self::_getSmartPath($assetId);
        self::copyToAssetFolder($path, $dataOrFile);
        $asset = new Asset();
        $asset->filename = $filename;
        $asset->asset_id = $assetId;
        $asset->mimeType = UtilsAbstract::getMimeType($filename);
        $asset->path = $path;
        $asset->type = trim($type);
        $asset->save();
        return $asset;
    }
    public static function getRootPath()
    {
        return env('APP_ASSET_ROOT_DIR');
    }
    private static function _getSmartPath($assetId)
    {
        $now = new Carbon();
        $year = $now->format('Y');
        if(!is_dir($yearDir = trim(self::getRootPath() .DIRECTORY_SEPARATOR . $year)))
        {
            mkdir($yearDir);
            chmod($yearDir, 0777);
        }
        $month = $now->format('m');
        if(!is_dir($monthDir = trim($yearDir .DIRECTORY_SEPARATOR . $month)))
        {
            mkdir($monthDir);
            chmod($monthDir, 0777);
        }
        return $monthDir . DIRECTORY_SEPARATOR . $assetId;
    }
}
