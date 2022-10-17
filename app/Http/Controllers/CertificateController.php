<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class CertificateController extends BaseController
{
    public function generate($branch, $number, $document_issuer, $document_receiver){

        $date = new \DateTime();
        $createdAt = Str::padTimestamp($date->getTimestamp());
        $seconds = (60 * 60 * 24 * 365 * 4);
        $expirationAt = Str::padTimestamp($createdAt + $seconds);

        $document_issuer = Str::padDocument($document_issuer);
        $document_receiver = Str::padDocument($document_receiver);

        $version = $this->getVersion();

        return "{$createdAt}{$expirationAt}{$branch}{$number}{$document_issuer}{$document_receiver}{$version}";//
    }

    protected function getVersion(){
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
