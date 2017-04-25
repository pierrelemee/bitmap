<?php

namespace Misc;

class User
{
    public $parent;
    protected $brother;
    protected $sister;
    protected $nephew;
    public $uncles;
    protected $aunts;
    protected $cousins;
    protected $children;
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

    /**
     * @return mixed
     */
    public function getAunts()
    {
        return $this->aunts;
    }

    /**
     * @return mixed
     */
    public function getCousins()
    {
        return $this->cousins;
    }

    /**
     * @param mixed $cousins
     */
    public function setCousins($cousins)
    {
        $this->cousins = $cousins;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function addChildren($children)
    {
        $this->children = $children;
    }
}