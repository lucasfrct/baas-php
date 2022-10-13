<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\AccountController as BaseController;

class AccountController extends BaseController
{
    public function generateCertificate($branch, $number, $document_issuer, $document_receiver){

        $date = new \DateTime();
        $createdAt = $date->getTimestamp();
        $seconds = (60 * 60 * 24 * 365 * 4);
        $expirationAt = $createdAt + $seconds;

        $document_issuer = $this->padDocument($document_issuer);
        $document_receiver = $this->padDocument($document_receiver);

        $createdAt = $this->padTimestamp($createdAt);
        $expirationAt = $this->padTimestamp($expirationAt);

        $verion = $this->getVersion();

        return "{$createdAt}{$expirationAt}{$branch}{$number}{$document_issuer}{$document_receiver}{$version}";
    }

    public function padDocument($document){
        return str_pad($document, 14, 'X', STR_PAD_LEFT);
    }

    public function padTimestamp($timestamp){
        return str_pad($timestamp, 10, '0', STR_PAD_LEFT);
    }

    public function getVersion(){
        return "00";
    }


}