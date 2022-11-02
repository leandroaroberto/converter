<?php

namespace App\Services;

use Illuminate\Http\Request;

class UrlInfo 
{
    public $header;
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
        $this->setData();
    }

    public function setData() 
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        $this->header = curl_exec($curl);
        curl_close($curl);
    }

    public function getHeader()
    {
        return $this->header;
    }
}
