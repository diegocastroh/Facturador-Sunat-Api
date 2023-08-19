<?php

namespace App\Services;

use DateTime;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Illuminate\Http\Request;

class InvoiceBuilder
{
    private Client $client;
    private Company $company;
    private SaleDetail $item;
    private Legend $legend;

    public function __construct(Client $client, Company $company, SaleDetail $item, Legend $legend)
    {
        $this->company = $company;
        $this->client = $client;
        $this->item = $item;
        $this->legend = $legend;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     */
    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }

    /**
     * @return SaleDetail
     */
    public function getItem(): SaleDetail
    {
        return $this->item;
    }

    /**
     * @param SaleDetail $item
     */
    public function setItem(SaleDetail $item): void
    {
        $this->item = $item;
    }

    /**
     * @return Legend
     */
    public function getLegend(): Legend
    {
        return $this->legend;
    }

    /**
     * @param Legend $legend
     */
    public function setLegend(Legend $legend): void
    {
        $this->legend = $legend;
    }



    public function buildInvoice()
    {
        // Configurar factura
        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101') // Venta - Catalog. 51
            ->setTipoDoc('01') // Factura - Catalog. 01
            ->setSerie('F005')
            ->setCorrelativo('1')
            ->setFechaEmision(new DateTime('2020-08-24 13:05:00-05:00')) // Zona horaria: Lima
            ->setFormaPago(new FormaPagoContado()) // FormaPago: Contado
            ->setTipoMoneda('PEN') // Sol - Catalog. 02
            ->setMtoOperGravadas(100.00)
            ->setMtoIGV(18.00)
            ->setTotalImpuestos(18.00)
            ->setValorVenta(100.00)
            ->setSubTotal(118.00)
            ->setMtoImpVenta(118.00);

        //$invoice->setDetails([$item])->setLegends([$legend]);

        //Inserción de datos
        $invoice->setCompany($this->company)
                ->setClient($this->client)
                ->setDetails([$this->item])
                ->setLegends([$this->legend]);



        return $invoice;
    }
}
