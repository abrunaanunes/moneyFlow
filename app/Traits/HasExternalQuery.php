<?php 

namespace App\Traits;

trait HasExternalQuery {
    
    protected function getQuery($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        return json_decode(curl_exec($curl));
    }
}