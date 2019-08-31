<?php
class Helper {
    
    public function addProtocol($url) {

        $issetHttp = strpos($url, 'http://');
        $issetHttps = strpos($url, 'https://');

        if ($issetHttp === false && $issetHttps === false) {
            $url = 'http://'.$url;
        }

        return $url;
    }

    public function addProtocolToPhoto($url) {

        $issetHttp = strpos($url, 'http://');
        $issetHttps = strpos($url, 'https://');

        if ($issetHttp === false && $issetHttps === false) {
            if ($url[0]=="/") {
                $url = 'http:/'.$url;
            } else {
                $url = 'http://'.$url;
            }
        }

        return $url;
    }

    public function deleteProtocol($url) {

        $url = str_replace('http://', '', $url);
        $url = str_replace('https://', '', $url);

        return $url;
    }

    public function getDomain($url) {
        $url = $this -> deleteProtocol($url);
        $pieces = explode('/', $url);
        return $pieces[0];
    }

    public function writeCSV($array, $domain) {
        $fp = fopen($domain.'.csv', 'w');

        foreach ($array as $fields) {
            fputcsv($fp, $fields, ';');
        }

        fclose($fp);
    }

    public function getDomainStats($domain) {
        $fp = fopen($domain.'.csv', 'r');

        return fgetcsv($fp, 1000, ";");
    }
}
?>