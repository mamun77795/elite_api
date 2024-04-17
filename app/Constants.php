<?php

namespace App;

class Constants
{
    private $STORAGE_PATH;
    private $ADMIN_APP_VERSION;
    private $USER_APP_VERSION;
    private $STORAGE_PATH_ARCHIVE;
    private $PUSH_API_KEY_USER;
    private $PUSH_API_KEY_ADMIN;

    public function __construct()
    {
        // live
        //$this->STORAGE_PATH = "http://etracker.smartmux.com/storage/app/public/";

        // live for getco
        //$this->STORAGE_PATH = "http://api.salesassistant.live/storage/app/public/";

        $this->STORAGE_PATH = "http://club.elitepaint.com.bd//storage/";
        $this->STORAGE_PATHWEB = "http://somriddhi.elitepaint.com.bd/images/";

        //local
        //$this->STORAGE_PATH = "http://192.168.0.112/etracker/storage/app/public/";

        $this->ADMIN_APP_VERSION = "1.0.0.1";
        // $this->USER_APP_VERSION = "1.0.0.001";
        //$this->PUSH_API_KEY_USER = "AAAA_rPzLpA:APA91bHnX2G0h3m4wNf0AJePFU0AreKMLCZz2x3sJRXXyiKSd8Y0m41WPQ1nc__RGWJUczTSR1M3oQfk1J8LULFboKgY289X9rCVy2E5SNfQpXDcplzskZs02ZUjBVr8h_9vqeyUwYzI";
        $this->PUSH_API_KEY_USER = "AAAAIBH3HP4:APA91bE8RKT-raB5XxOrxdIAPXmfXHMcjGPfAVuQQvuojS3HaDrhwDU-3fEjlo9KRWbis39SQuqLSpOG0Dc3br7633LokeaCUdh4QVJZN07TtV3A8GKp_zUFqQoHpeyA6fW31bNflNTa";
        $this->PUSH_API_KEY_ADMIN = "AAAAIBH3HP4:APA91bE8RKT-raB5XxOrxdIAPXmfXHMcjGPfAVuQQvuojS3HaDrhwDU-3fEjlo9KRWbis39SQuqLSpOG0Dc3br7633LokeaCUdh4QVJZN07TtV3A8GKp_zUFqQoHpeyA6fW31bNflNTa";

        $this->erp_api_key = "DU-3fEjlo9KRWbis39SQuqLSpOG0Dc3br7633LokeaCUdh4QVJZN07TtV3A8GK.zU";
        $this->sms_api_key = "raB5XxOrxdIAPXmfXHMcjGPfAV.uQQvuojS3HaDH3HP4:APA91bE8RKT-raHpeyA6f";
    }
    public function getStoragePathWeb(){
        return $this->STORAGE_PATHWEB;
    }
    public function getStoragePath(){
        return $this->STORAGE_PATH;
    }
    public function geterp_api_key(){
        return $this->erp_api_key;
    }
    public function getsms_api_key(){
        return $this->sms_api_key;
    }
    public function getPushApiKeyUser(){
        return $this->PUSH_API_KEY_USER;
    }
    public function getPushApiKeyAdmin(){
        return $this->PUSH_API_KEY_ADMIN;
    }
    public function getAppVersionAdmin(){
        return $this->PUSH_API_KEY_ADMIN;
    }
    // public function getAppVersionUser(){
    //     return $this->USER_APP_VERSION;
    // }
}
