<?php 

namespace App\Helpers;

class HttpClient{

    public function post(string $url, array $data = []){
        $handler = curl_init();

        curl_setopt($handler, CURLOPT_URL, $url);
        curl_setopt($handler, CURLOPT_POST, true);
        curl_setopt($handler, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($handler);
        $statusCode = curl_getinfo($handler, CURLINFO_HTTP_CODE); 
        curl_close($handler);

        return json_encode(["statusCode" => $statusCode, "content" => $response]);
    }

    //this can also have data, but we do need that for this example
    public function get(string $url){
        $handler = curl_init();

        curl_setopt($handler, CURLOPT_URL, $url);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($handler);
        $statusCode = curl_getinfo($handler, CURLINFO_HTTP_CODE);    //check for 200 to show request is successful
        curl_close($handler);

        return json_encode(["statusCode" => $statusCode, "content" => $response]);
    }

}


?>