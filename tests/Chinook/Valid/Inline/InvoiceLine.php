<?php

namespace Chinook\Valid\Inline;

use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\Mapper;

class InvoiceLine extends Entity
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

    public function initializeMapper(Mapper $mapper)
    {
        $mapper
            ->addPrimary('id', Bitmap::TYPE_INTEGER, 'InvoiceLineId')
            ->addAssociationOne('invoice', Invoice::class, 'InvoiceId')
            ->addAssociationOne('track', Track::class, 'TrackId')
            ->addField('unitPrice', Bitmap::TYPE_FLOAT, 'UnitPrice')
            ->addField('quantity', Bitmap::TYPE_INTEGER, 'Quantity');
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