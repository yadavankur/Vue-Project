<?php
namespace App\Models\Utils;

use App\Models\Entities\User;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;

Abstract class UtilsAbstract
{
    const CURL_TIMEOUT = 360000;

    /**
     * get Random Key
     * @return string
     */
    public static function getRandKey()
    {

        $user = Auth::user();
        if (!$user instanceof User)
        {
            $userId = env('APP_SYSTEM_USER_ID');
            $user = User::findOrFail($userId);
        }
        $userEmail = $user->email;
        $time = new DateTime();
        return md5(implode('_', [$userEmail, $time->getTimestamp(), random_int(0, PHP_INT_MAX)]));
    }
    /**
     * Get mime type of the file
     * @param $filename
     * @return string
     */
    public static function getMimeType($filename)
    {
        preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);
        if(isset($fileSuffix[1]))
        {
            switch(strtolower($fileSuffix[1]))
            {
                case "js" :
                    return "application/x-javascript";

                case "json" :
                    return "application/json";

                case "jpg" :
                case "jpeg" :
                case "jpe" :
                    return "image/jpg";

                case "png" :
                case "gif" :
                case "bmp" :
                case "tiff" :
                    return "image/".strtolower($fileSuffix[1]);

                case "css" :
                    return "text/css";

                case "xml" :
                    return "application/xml";

                case "doc" :
                case "docx" :
                    return "application/msword";

                case "xls" :
                case "xlt" :
                case "xlm" :
                case "xld" :
                case "xla" :
                case "xlc" :
                case "xlw" :
                case "xll" :
                    return "application/vnd.ms-excel";

                case "ppt" :
                case "pps" :
                    return "application/vnd.ms-powerpoint";

                case "rtf" :
                    return "application/rtf";

                case "pdf" :
                    return "application/pdf";

                case "html" :
                case "htm" :
                case "php" :
                    return "text/html";

                case "txt" :
                    return "text/plain";

                case "mpeg" :
                case "mpg" :
                case "mpe" :
                    return "video/mpeg";

                case "mp3" :
                    return "audio/mpeg3";

                case "wav" :
                    return "audio/wav";

                case "aiff" :
                case "aif" :
                    return "audio/aiff";

                case "avi" :
                    return "video/msvideo";

                case "wmv" :
                    return "video/x-ms-wmv";

                case "mov" :
                    return "video/quicktime";

                case "zip" :
                    return "application/zip";

                case "tar" :
                    return "application/x-tar";

                case "swf" :
                    return "application/x-shockwave-flash";

                default :
            }
        }
        return isset($fileSuffix[0]) ? "unknown/" . trim($fileSuffix[0], ".") : "text/plain";
    }

    /**
     * @param $fullPath
     * @return mixed
     */
    public static function getRealPath($fullPath)
    {
        // replace v: with /home/V6_3_Quotes/
        $search = '\\';
        $replace = '/';
        $fileName = str_replace($search, $replace, $fullPath);
        $storagePath = env('APP_STORAGE_QUOTES');
        $fileNameWithPath = str_replace('v:', $storagePath, $fileName);
        return $fileNameWithPath;
    }

    /**
     * @param $string
     * @param $length
     * @param string $dots
     * @return string
     */
    public static function truncate($string, $length, $dots = "...")
    {
        return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
    }

    public static function readUrl($url, $timeout = null, array $data = array(), $customerRequest = '', $extraOpts = array())
    {
        $timeout = trim($timeout);
        $timeout = (!is_numeric($timeout) ? self::CURL_TIMEOUT : $timeout);
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_TIMEOUT => $timeout, // set this to 8 hours so we dont timeout on big files
            CURLOPT_URL     => $url
        );
        foreach(self::_getProxy() as $key => $value)
            $options[$key] = $value;
        foreach($extraOpts as $key => $value)
            $options[$key] = $value;
        if(count($data) > 0)
        {
            if(trim($customerRequest) === '')
                $options[CURLOPT_POST] = true;
            else
                $options[CURLOPT_CUSTOMREQUEST] = $customerRequest;
            $options[CURLOPT_POSTFIELDS] = http_build_query($data);
        }
        $ch = curl_init();
        foreach($options as $key => $value)
            curl_setopt($ch, $key, $value);
        $data =curl_exec($ch);
        if ($data === false)
        {
            throw new Exception(curl_error($ch), curl_errno($ch));
        }
        curl_close($ch);
        return $data;
    }
    private static function _getProxy() {
        return array(
//            CURLOPT_PROXY   => 'localhost:3128',
//            CURLOPT_PROXYTYPE => CURLPROXY_SOCKS5
        );
    }
}