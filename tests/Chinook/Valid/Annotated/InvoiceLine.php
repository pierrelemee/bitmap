<?php

namespace Chinook\Valid\Annotated;

use Bitmap\AnnotatedEntity;

class InvoiceLine extends AnnotatedEntity
{
    /**
     * @primary InvoiceLineId
     * @type integer
     * @var integer $id
     */
    protected $id;
    /**
     * @association Chinook\Valid\Annotated\Invoice
     * @type one InvoiceId
     * @var Invoice $invoice
     */
    protected $invoice;
    /**
     * @association Chinook\Valid\Annotated\Track
     * @type one TrackId
     * @var Track $track
     */
    protected $track;
    /**
     * @field UnitPrice
     * @type float
     * @var float $unitPrice
     */
    protected $unitPrice;
    /**
     * @field Quantity
     * @type integer
     * @var integer $quantity
     */
    protected $quantity;

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