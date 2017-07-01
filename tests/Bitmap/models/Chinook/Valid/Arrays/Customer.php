<?php

namespace Chinook\Valid\Arrays;

use Bitmap\ArrayMappedEntity;
use Bitmap\Bitmap;
use Chinook\Valid\Arrays\Employee;

class Customer extends ArrayMappedEntity
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

    protected function getMappingArray()
    {
        return [
            'primary' => [
                'name'   => 'id',
                'column' => 'CustomerId',
                'type'   => Bitmap::TYPE_INTEGER
            ],
            'fields' => [
                'firstname' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ],
                'lastname' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ],
                'company' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ],
                'address' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ],
                'city' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ],
                'state' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ],
                'country' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ],
                'postalCode' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ],
                'phone' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ],
                'fax' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ],
                'email' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ]
            ],
            'associations' => [
                'referrer' => [
                    'class' => Employee::class,
                    'column' => 'SupportRepId'
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