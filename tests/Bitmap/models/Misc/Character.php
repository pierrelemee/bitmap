<?php

namespace Misc;

use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\Mapper;

class Character extends Entity
{
    protected $id;
    protected $firstName;
    protected $lastName;
    /**
     * @var Character
     */
    protected $father;
    /**
     * @var Character
     */
    protected $mother;

    public function initializeMapper(Mapper $mapper)
    {
        $mapper
            ->addPrimary('id', Bitmap::TYPE_INTEGER)
            ->addField('firstName', Bitmap::TYPE_STRING)
            ->addField('lastName', Bitmap::TYPE_STRING)
            ->addAssociationOne('father', __CLASS__)
            ->addAssociationOne('mother', __CLASS__);
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
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return Character
     */
    public function getFather()
    {
        return $this->father;
    }

    /**
     * @param Character $father
     */
    public function setFather($father)
    {
        $this->father = $father;
    }

    /**
     * @return Character
     */
    public function getMother()
    {
        return $this->mother;
    }

    /**
     * @param Character $mother
     */
    public function setMother($mother)
    {
        $this->mother = $mother;
    }
}