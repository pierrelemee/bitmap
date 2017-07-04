<?php

namespace Chinook\Valid\Arrays;

use Bitmap\ArrayMappedEntity;
use Bitmap\Bitmap;

class InvoiceLine extends ArrayMappedEntity
{
    protected $id;
    /**
     * @var Invoice $invoice
     */
    protected $invoice;
    /**
     * @var Track $track
     */
    protected $track;
    protected $unitPrice;
    protected $quantity;

    protected function getMappingArray()
    {
        return [
            'primary' => [
                'name'   => 'id',
                'column' => 'InvoiceLineId',
                'type'   => Bitmap::TYPE_INTEGER
            ],
            'fields' => [
                'unitPrice' => [
                    'column' => 'UnitPrice',
                    'type'   => Bitmap::TYPE_FLOAT
                ],
                'quantity' => [
                    'column' => 'Quantity',
                    'type'   => Bitmap::TYPE_INTEGER
                ]
            ],
            'associations' => [
                'invoice' => [
                    'type'   => 'one',
                    'class'  => Invoice::class,
                    'column' => 'InvoiceId'
                ],
                'track' => [
                    'type'   => 'one',
                    'class'  => Track::class,
                    'column' => 'TrackId'
                ]

            ]
        ];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param Invoice $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return Track
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * @param Track $track
     */
    public function setTrack($track)
    {
        $this->track = $track;
    }

    /**
     * @return mixed
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param mixed $unitPrice
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
}