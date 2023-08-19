<?php

namespace App\Services;

use Exception;
use Greenter\Data\Generator\InvoiceStore;
use Greenter\Model\Sale\Invoice;
use Greenter\See;

class InvoiceSender
{
    public static function sendInvoice(See $see, Invoice $invoice)
    {

        $result = $see->send($invoice);

        $response = [];

        file_put_contents($invoice->getName() . '.xml',
            $see->getFactory()->getLastXml());

        if ($result->isSuccess()) {
            file_put_contents('R-' . $invoice->getName() . '.zip', $result->getCdrZip());
            $cdr = $result->getCdrResponse();

            $code = (int)$cdr->getCode();

            if ($code === 0) {
                $response['status'] = 'ACEPTADA' . PHP_EOL;
                if (count($cdr->getNotes()) > 0) {
                    $response['comments '] = PHP_EOL;
                    // Corregir estas observaciones en siguientes emisiones.
                    var_dump($cdr->getNotes());
                }
            } else if ($code >= 2000 && $code <= 3999) {
                $response['status'] = 'RECHAZADA' . PHP_EOL;
            } else {
                /* Esto no debería darse, pero si ocurre, es un CDR inválido que debería tratarse como un error-excepción. */
                /*code: 0100 a 1999 */
                $response['exception'] = $code;
            }

            $response['success'] = true;
            $response['message'] = 'Factura enviada exitosamente';
            $response['description'] = $cdr->getDescription() . PHP_EOL;
        } else {

            $response['success'] = false;
            $response['error'] = [
                'code' => $result->getError()->getCode(),
                'message' => $result->getError()->getMessage()
            ];
        }
        return $response;
    }

}
