<?php

namespace App\Util;
use Dompdf\Dompdf;
use Dompdf\Options;
use Greenter\Model\Sale\Invoice;
use Greenter\Report\PdfReport;
use Greenter\Report\Resolver\DefaultTemplateResolver;
use Greenter\Report\HtmlReport;

class GenerateReports
{


    public function htmlReport(Invoice $invoice):String{

        $report = new HtmlReport();

        $resolver = new DefaultTemplateResolver();
        $report->setTemplate($resolver->getTemplate($invoice));

        $params = [
            'system' => [
                'logo' => file_get_contents(storage_path('app/logo.png')), // Logo de Empresa
                'hash' => 'qqnr2dN4p/HmaEA/CJuVGo7dv5g=', // Valor Resumen
            ],
            'user' => [
                'header'     => 'Telf: <b>(01) 123375</b>', // Texto que se ubica debajo de la dirección de empresa
                'extras'     => [
                    // Leyendas adicionales
                    ['name' => 'CONDICION DE PAGO', 'value' => 'Efectivo'     ],
                    ['name' => 'VENDEDOR'         , 'value' => 'GITHUB SELLER'],
                ],
                'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
            ]
        ];

        $html = $report->render($invoice, $params);


        return $html;
    }


    /**
     * Crea reportes en pdf, requiere datos de la factura completos y el reporte en HTML realizado como STRING.
     * @param Invoice $invoice
     * @param String $html
     * @return void
     */
    public function pdfReport(Invoice $invoice, String $html){

        /*$htmlReport = new HtmlReport();
        $htmlReport->setTemplate('/invoice.html.twig');

        $report = new PdfReport($htmlReport);
// Options: Ver mas en https://wkhtmltopdf.org/usage/wkhtmltopdf.txt
        $report->setOptions( [
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
        ]);
        $report->setBinPath('wkhtmltopdf.exe'); // Ruta relativa o absoluta de wkhtmltopdf
// Los mismo parametros utilizados en HTML Report
        $params = [
            'system' => [
            'logo' => file_get_contents(storage_path('app/logo.png')), // Logo de Empresa
            'hash' => 'qqnr2dN4p/HmaEA/CJuVGo7dv5g=', // Valor Resumen
        ],
            'user' => [
                'header'     => 'Telf: <b>(01) 123375</b>', // Texto que se ubica debajo de la dirección de empresa
                'extras'     => [
                    // Leyendas adicionales
                    ['name' => 'CONDICION DE PAGO', 'value' => 'Efectivo'     ],
                    ['name' => 'VENDEDOR'         , 'value' => 'GITHUB SELLER'],
                ],
                'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
            ]];

        $pdf = $report->render($invoice, $params);

        if ($pdf === null) {
            $error = $report->getExporter()->getError();
            echo 'Error: '.$error;
            return;
        }

        file_put_contents(storage_path('invoice.pdf'), $pdf);*/



        // Crear una instancia de Dompdf con opciones personalizadas
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true); // Habilitar el análisis de HTML5
        $dompdf = new Dompdf($options);

        // Cargar el contenido HTML en Dompdf
        $dompdf->loadHtml($html);

        // (Opcional) Puedes establecer tamaños de papel y orientación aquí
         $dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF
        $dompdf->render();

        // Obtener el contenido del PDF generado
        $pdfContent = $dompdf->output();

        // Guardar el contenido en un archivo PDF
        file_put_contents(storage_path('invoice.pdf'), $pdfContent);
    }
}
