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

    public function getMapper()
    {
        $reflection = new ReflectionClass(__CLASS__);
        return Mapper::of(__CLASS__)
            ->addPrimary(
                MethodField::fromClass('EmployeeId', $reflection, 'id')
                ->setType(Bitmap::TYPE_INTEGER)
            )
            ->addAssociation(
                MethodAssociationOne::fromMethods(
                    'ReportsTo',
                    __CLASS__,
                    $reflection->getMethod('getSuperior'),
                    $reflection->getMethod('setSuperior'),
                    'EmployeeId'
                )
            )
            ->addField(
                MethodField::fromClass('LastName', $reflection, 'lastname')
                    ->setType(Bitmap::TYPE_STRING)
            )
            ->addField(
                MethodField::fromClass('FirstName', $reflection, 'firstname')
                    ->setType(Bitmap::TYPE_STRING)
            )
            ->addField(
                MethodField::fromClass('BirthDate', $reflection, 'bornAt')
                    ->setType(Bitmap::TYPE_DATETIME)
            )
            ->addField(
                MethodField::fromClass('HireDate', $reflection, 'hiredAt')
                    ->setType(Bitmap::TYPE_DATETIME)
            );
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