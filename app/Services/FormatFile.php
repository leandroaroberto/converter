<?php

namespace App\Services;

use Illuminate\Http\Request;

class FormatFile
{
    //A hotel name may not contain non-ASCII characters
    public static function formatName($str)
    {

        if(preg_match('/[^\x20-\x7f]/', $str))
        {            
            return '0';
        }   
        elseif($str != '')
        {
            return $str;
        }         
        else {
            return '0';
        }
    }

    //Hotel ratings are given as a number from 0 to 5 stars (never negative numbers).
    public static function formatStars($str)
    {        
        if(! is_numeric($str))
        {
            return -1;
        }
        if($str >=0 && $str <= 5)
        {
            return $str;
        }
        return -1;
        
    }

    //The hotel URL must be valid (please come up with a good definition of "valid").
    public static function formatUrl($str)
    {     
        if (filter_var($str, FILTER_VALIDATE_URL)) 
        {  
            return self::checkUrl($str) ? $str : 0;            
        } 
        else 
        {
            return 0;
        }
    }

    public static function formatAddress($str)
    {
        return $str;
    }

    public static function formatContact($str)
    {
        return $str;
    }

    public static function formatPhone($str)
    {
        return $str;
    }


    public static function checkUrl($url)
    {  
        $urlIsValid = false;
        $file_headers = @get_headers($url);

        if(!$file_headers) {
            $urlIsValid = false;
        }
        else {
            if (strpos($file_headers[0], "200 OK")) {
                $urlIsValid = true;
            }
        }

        return $urlIsValid;
    }
}
