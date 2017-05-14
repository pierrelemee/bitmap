<?php

namespace Chinook\Valid\Inline;

use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\Mapper;

class Customer extends Entity
{
    protected $id;
    protected $firstname;
    protected $lastname;
    protected $company;
    protected $address;
    protected $city;
    protected $state;
    protected $country;
    protected $postalCode;
    protected $phone;
    protected $fax;
    protected $email;
    /**
     * @var Employee $referrer
     */
    protected $referrer;

    public function initializeMapper(Mapper $mapper)
    {
        $mapper
            ->addPrimary('id', Bitmap::TYPE_INTEGER, 'CustomerId')
            ->addField('firstname', Bitmap::TYPE_STRING, 'FirstName')
            ->addField('lastname', Bitmap::TYPE_STRING, 'LastName')
            ->addField('company', Bitmap::TYPE_STRING, 'Company')
            ->addField('address', Bitmap::TYPE_STRING, 'Address')
            ->addField('city', Bitmap::TYPE_STRING, 'City')
            ->addField('state', Bitmap::TYPE_STRING, 'State')
            ->addField('country', Bitmap::TYPE_STRING, 'Country')
            ->addField('postalCode', Bitmap::TYPE_STRING, 'PostalCode')
            ->addField('phone', Bitmap::TYPE_STRING, 'Phone')
            ->addField('fax', Bitmap::TYPE_STRING, 'Fax')
            ->addField('email', Bitmap::TYPE_STRING, 'Email')
            ->addAssociationOne('referrer', Employee::class, 'SupportRepId');
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