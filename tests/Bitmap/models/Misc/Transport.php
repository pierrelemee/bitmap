<?php

namespace Misc;

use Bitmap\Entity;
use Bitmap\Fields\PropertyField;
use Bitmap\Mapper;
use ReflectionClass;

class Transport extends Entity
{
    public $id;

    public function getMapper()
    {
        $mapper = new Mapper(__CLASS__);
        $reflection = new ReflectionClass(__CLASS__);
        $mapper->addPrimary(new PropertyField('id', $reflection->getProperty('id')));

        return $mapper;
    }

}