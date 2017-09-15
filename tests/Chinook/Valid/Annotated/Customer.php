<?php

namespace Chinook\Valid\Annotated;

use Bitmap\AnnotatedEntity;

class Customer extends AnnotatedEntity
{
    /**
     * @primary CustomerId
     * @type integer
     * @var int
     */
    protected $id;
    /**
     * @field FirstName
     * @type string
     * @var string
     */
    protected $firstname;
    /**
     * @field LastName
     * @type string
     * @var string
     */
    protected $lastname;
    /**
     * @field Company
     * @type string
     * @var string
     */
    protected $company;
    /**
     * @field Address
     * @type string
     * @var string
     */
    protected $address;
    /**
     * @field FirstName
     * @type string
     * @var string
     */
    protected $city;
    /**
     * @field State
     * @type string
     * @var string
     */
    protected $state;
    /**
     * @field Country
     * @type string
     * @var string
     */
    protected $country;
    /**
     * @field PostalCode
     * @type string
     * @var string
     */
    protected $postalCode;
    /**
     * @field Phone
     * @type string
     * @var string
     */
    protected $phone;
    /**
     * @field Fax
     * @type string
     * @var string
     */
    protected $fax;
    /**
     * @field Email
     * @type string
     * @var string
     */
    protected $email;
    /**
     * @association Chinook\Valid\Annotated\Employee
     * @type one SupportRepId
     * @var Employee $referrer
     */
    protected $referrer;

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
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
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
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param mixed $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return Employee
     */
    public function getReferrer()
    {
        return $this->referrer;
    }

    /**
     * @param Employee $referrer
     */
    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;
    }
}