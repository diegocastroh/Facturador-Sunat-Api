<?php

namespace App\Http\Controllers;

use App\Services\CertificateProvider;
use App\Services\InvoiceBuilder;
use App\Services\InvoiceSender;
use App\Util\GenerateReports;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    //
    public function sendInvoice(Request $request)
    {
        $data = $request->json()->all();

        $see = CertificateProvider::getSeeInstance();

        $invoiceBuilder = new InvoiceBuilder($data);
        $invoice = $invoiceBuilder->buildInvoice();

        $response = InvoiceSender::sendInvoice($see, $invoice);

        return response()->json($response);
    }

    public function generateReportPDF(){
        $data = array();

        $see = CertificateProvider::getSeeInstance();

        $client = (new Client())
            ->setTipoDoc('6')
            ->setNumDoc('20000000001')
            ->setRznSocial('EMPRESA X');

        // Emisor
        $address = (new Address())
            ->setUbigueo('150101')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('LIMA')
            ->setUrbanizacion('-')
            ->setDireccion('Av. Villa Nueva 221')
            ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

        $company = (new Company())
            ->setRuc('20123456789')
            ->setRazonSocial('GREEN SAC')
            ->setNombreComercial('GREEN')
            ->setAddress($address);

        $item = (new SaleDetail())
            ->setCodProducto('P001')
            ->setUnidad('NIU') // Unidad - Catalog. 03
            ->setCantidad(2)
            ->setMtoValorUnitario(50.00)
            ->setDescripcion('PRODUCTO 1')
            ->setMtoBaseIgv(100)
            ->setPorcentajeIgv(18.00) // 18%
            ->setIgv(18.00)
            ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
            ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
            ->setMtoValorVenta(100.00)
            ->setMtoPrecioUnitario(59.00)
        ;

        $legend = (new Legend())
            ->setCode('1000') // Monto en letras - Catalog. 52
            ->setValue((new ConvertController())
                ->convertNumberToWords(118));

        $invoiceBuilder = new InvoiceBuilder($client,$company,$item,$legend);
        $invoice = $invoiceBuilder->buildInvoice();

        $response = InvoiceSender::sendInvoice($see, $invoice);

        $report = new GenerateReports();
        $html = $report->htmlReport($invoice);

        $report->pdfReport($invoice, $html);

        return response()->json($response);
    }
}
