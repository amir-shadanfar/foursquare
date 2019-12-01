<?php

namespace App\Classes;

use App\Interfaces\ApiInterface;

class ApiClass implements ApiInterface
{
    protected $method;
    protected $url;
    protected $data = [];

    /**
     * @return Json
     */
    public function callApi()
    {
        $url = $this->getUrl();
        $method = $this->getMethod();
        $data = $this->getData();
        $query = null;
        $auth = [
            'client_id'     => env('FOURSQUARE_CLIENT_ID'),
            'client_secret' => env('FOURSQUARE_CLIENT_SECRET'),
            'v'             => '20191129'
        ];
        // add auth params as parameters
        if (strpos($url, '?') == false) {
            $url = sprintf("%s?%s", $url, http_build_query($auth));
        } else {
            $url = sprintf("%s&%s", $url, http_build_query($auth));
        }
        // curl
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if (count($data))
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if (count($data))
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if (count($data)) {
                    $query = http_build_query($data);
                }
        }
        // OPTIONS:
        if (!is_null($query)) {
            $url = !$url . '&' . $query;
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        // dd($result);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        return $result;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method): void
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }


}