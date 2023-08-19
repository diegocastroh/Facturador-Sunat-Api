<?php

namespace Tests\Feature;

use App\Http\Controllers\ApiController;
use Tests\TestCase;

class ApiControllerTest extends TestCase
{
    public function testSendInvoice()
    {
        $requestData = [
            'client' => [
                'tipoDoc' => '6',
                'numDoc' => '20000000001',
                'rznSocial' => 'EMPRESA X'
            ],
            'company' => [
                'ruc' => '20123456789',
                'razonSocial' => 'GREEN SAC',
                'nombreComercial' => 'GREEN'
            ],
            'address' => [
                'ubigueo' => '150101',
                'departamento' => 'LIMA',
                'provincia' => 'LIMA',
                'distrito' => 'LIMA',
                'urbanizacion' => '-',
                'direccion' => 'Av. Villa Nueva 221',
                'codLocal' => '0000'
            ],
            'invoice' => [
                'ublVersion' => '2.1',
                'tipoOperacion' => '0101',
                'tipoDoc' => '01',
                'serie' => 'F001',
                'correlativo' => '1',
                'fechaEmision' => '2020-08-24T13:05:00-05:00',
                'formaPago' => [
                    'type' => 'FormaPagoContado'
                ],
                'tipoMoneda' => 'PEN',
                'company' => [
                    'type' => 'Company'
                ],
                'client' => [
                    'type' => 'Client'
                ],
                'mtoOperGravadas' => 100.0,
                'mtoIGV' => 18.0,
                'totalImpuestos' => 18.0,
                'valorVenta' => 100.0,
                'subTotal' => 118.0,
                'mtoImpVenta' => 118.0
            ],
            'item' => [
                'codProducto' => 'P001',
                'unidad' => 'NIU',
                'cantidad' => 2,
                'mtoValorUnitario' => 50.0,
                'descripcion' => 'PRODUCTO 1',
                'mtoBaseIgv' => 100,
                'porcentajeIgv' => 18.0,
                'igv' => 18.0,
                'tipAfeIgv' => '10',
                'totalImpuestos' => 18.0,
                'mtoValorVenta' => 100.0,
                'mtoPrecioUnitario' => 59.0
            ],
            'legend' => [
                'code' => '1000',
                'value' => 'SON DOSCIENTOS TREINTA Y SEIS CON 00/100 SOLES'
            ]
        ];


        $response = $this->postJson('/api/sendinvoice', $requestData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                // Otras claves esperadas en la respuesta JSON
            ]);

        // Puedes agregar más aserciones para verificar el contenido de la respuesta según el resultado del envío de la factura.
    }

    public function testReport(){
        $response = $this->get('/api/testReport');
        $response->assertStatus(200)
        ->assertViewIs("pdf");
    }
}
