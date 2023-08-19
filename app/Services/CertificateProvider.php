<?php

namespace App\Services;

namespace App\Services;

use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;

class CertificateProvider
{
    public static function getSeeInstance()
    {
        $see = new See();
        $see->setCertificate(file_get_contents(storage_path('app/certificate.pem')));
        $see->setService(SunatEndpoints::FE_BETA);
        $see->setClaveSOL('20000000001', 'MODDATOS', 'moddatos');

        return $see;
    }
}
