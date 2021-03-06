<?php

namespace Chinook\Valid\Inline;

use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\Mapper;

class Invoice extends Entity
{
    protected $id;
    /**
     * @var Customer $customer
     */
    protected $customer;
    protected $date;
    /**
     * @var InvoiceLine[] $lines
     */
    protected $lines;
    protected $address;
    protected $city;
    protected $state;
    protected $country;
    protected $postalCode;
    protected $total;

    public function initializeMapper(Mapper $mapper)
    {
        $mapper
            ->addPrimary('id', Bitmap::TYPE_INTEGER, 'InvoiceId')
            ->addField('date', Bitmap::TYPE_DATETIME, 'InvoiceDate')
            ->addAssociationOne('customer', Customer::class, 'CustomerId')
            ->addAssociationOneToMany('lines', InvoiceLine::class, 'InvoiceId')
            ->addField('address', Bitmap::TYPE_STRING, 'BillingAddress')
            ->addField('city', Bitmap::TYPE_STRING, 'BillingCity')
            ->addField('state', Bitmap::TYPE_STRING, 'BillingState')
            ->addField('country', Bitmap::TYPE_STRING, 'BillingCountry')
            ->addField('postalCode', Bitmap::TYPE_STRING, 'BillingPostalCode')
            ->addField('total', Bitmap::TYPE_FLOAT, 'Total');
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
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return InvoiceLine[]
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @param InvoiceLine[] $lines
     */
    public function setLines($lines)
    {
        $this->lines = $lines;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }
}