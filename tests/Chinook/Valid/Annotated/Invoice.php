<?php

namespace Chinook\Valid\Annotated;

use Bitmap\AnnotatedEntity;
use Bitmap\Bitmap;
use Bitmap\Mapper;

class Invoice extends AnnotatedEntity
{
    /**
     * @primary InvoiceId
     * @type integer
     * @var int $id
     */
    protected $id;
    /**
     * @association Chinook\Valid\Annotated\Customer
     * @type one CustomerId
     * @var Customer $customer
     */
    protected $customer;
    /**
     * @field InvoiceDate
     * @type datetime
     * @var \DateTime $date
     */
    protected $date;
    /**
     * @association Chinook\Valid\Annotated\InvoiceLine
     * @type one-to-many InvoiceId
     * @var InvoiceLine[] $lines
     */
    protected $lines;
    /**
     * @field Address
     * @type string
     * @var string $address
     */
    protected $address;
    /**
     * @field City
     * @type string
     * @var string $city
     */
    protected $city;
    /**
     * @field State
     * @type string
     * @var string $state
     */
    protected $state;
    /**
     * @field Country
     * @type string
     * @var string $country
     */
    protected $country;
    /**
     * @field PostalCode
     * @type string
     * @var string $postalCode
     */
    protected $postalCode;
    /**
     * @field Total
     * @type float
     * @var float $total
     */
    protected $total;

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