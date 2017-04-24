<?php

namespace Misc;

class User
{
    public $parent;
    protected $brother;
    protected $sister;
    protected $nephew;
    protected $car;

    /**
     * @return mixed
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param mixed $car
     */
    public function setCar($car)
    {
        $this->car = $car;
    }

    /**
     * @return mixed
     */
    public function getSister()
    {
        return $this->sister;
    }

    /**
     * @return mixed
     */
    public function getNephew()
    {
        return $this->nephew;
    }

    /**
     * @param mixed $nephew
     */
    public function addNephew($nephew)
    {
        $this->nephew = $nephew;
    }


}