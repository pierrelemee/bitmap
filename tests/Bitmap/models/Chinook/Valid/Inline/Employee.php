<?php

namespace Chinook\Valid\Inline;

use Bitmap\Associations\MethodAssociationOne;
use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\Fields\MethodField;
use Bitmap\Mapper;
use ReflectionClass;

class Employee extends Entity
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

    public function initializeMapper(Mapper $mapper)
    {
        $mapper
            ->addPrimary('id', Bitmap::TYPE_INTEGER, 'EmployeeId')
            ->addAssociationOne('superior', Employee::class, 'ReportsTo')
            ->addField('lastname', Bitmap::TYPE_STRING, 'LastName')
            ->addField('firstname', Bitmap::TYPE_STRING, 'FirstName')
            ->addField('title', Bitmap::TYPE_STRING, 'Title')
            ->addField('bornAt', Bitmap::TYPE_DATETIME, 'BirthDate')
            ->addField('hiredAt', Bitmap::TYPE_DATETIME, 'HireDate');
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