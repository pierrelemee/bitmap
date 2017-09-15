<?php

namespace Chinook\Valid\Annotated;

use Bitmap\AnnotatedEntity;

class Employee extends AnnotatedEntity
{
    /**
     * @primary EmployeeId
     * @type integer
     * @var int
     */
    protected $id;
    /**
     * @association Chinook\Valid\Annotated\Employee
     * @type one ReportsTo
     * @var Employee $superior
     */
    protected $superior;
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
     * @field Title
     * @type string
     * @var string
     */
    protected $title;
    /**
     * @field BirthDate
     * @type string
     * @var string
     */
    protected $bornAt;
    /**
     * @field HireDate
     * @type datetime
     * @var string
     */
    protected $hiredAt;

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