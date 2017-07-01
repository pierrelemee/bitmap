<?php

namespace Chinook\Valid\Arrays;

use Bitmap\ArrayMappedEntity;
use Bitmap\Bitmap;

class Employee extends ArrayMappedEntity
{
    protected $id;
    /**
     * @var Employee
     */
    protected $superior;
    protected $firstname;
    protected $lastname;
    protected $title;
    protected $bornAt;
    protected $hiredAt;

    protected function getMappingArray()
    {
        return [
            'primary' => [
                'name'   => 'id',
                'column' => 'EmployeeId',
                'type'   => Bitmap::TYPE_INTEGER
            ],
            'fields' => [
                'lastname' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ],
                'firstname' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ],
                'title' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_STRING
                ],
                'bornAt' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_DATETIME
                ],
                'hiredAt' => [
                    'column' => 'FirstName',
                    'type' => Bitmap::TYPE_DATETIME
                ]
            ],
            'associations' => [
                'artist' => [
                    'type'   => 'one',
                    'class'  => __CLASS__,
                    'column' => 'ReportsTo'
                ],
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
     * @return Employee
     */
    public function getSuperior()
    {
        return $this->superior;
    }

    /**
     * @param Employee $superior
     */
    public function setSuperior($superior)
    {
        $this->superior = $superior;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getBornAt()
    {
        return $this->bornAt;
    }

    /**
     * @param mixed $bornAt
     */
    public function setBornAt($bornAt)
    {
        $this->bornAt = $bornAt;
    }

    /**
     * @return mixed
     */
    public function getHiredAt()
    {
        return $this->hiredAt;
    }

    /**
     * @param mixed $hiredAt
     */
    public function setHiredAt($hiredAt)
    {
        $this->hiredAt = $hiredAt;
    }
}