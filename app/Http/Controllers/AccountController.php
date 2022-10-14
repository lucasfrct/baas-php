<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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

        $version = $this->getVersion();

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

    public function certificateDisruption($certificate){

        return $certificateProperies = array(
        'timestamp' => substr($certificate, 0, 10),
        'expiration' => substr($certificate, 10, 10),
        'branch' => substr($certificate, 20, 4),
        'number' => substr($certificate, 24, 6),
        'document_issuer' => substr($certificate, 30, 14),
        'document_receiver' => substr($certificate, 44, 14),
        'version' => substr($certificate, 58, 2),
        );
        
    }
}