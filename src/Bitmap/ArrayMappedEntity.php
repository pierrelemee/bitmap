<?php

namespace Bitmap;

use Bitmap\Mappers\ArrayMapper;

abstract class ArrayMappedEntity extends Entity
{
    public function createMapper()
    {
        return new ArrayMapper($this->mapping());
    }

    protected function mapping()
    {
        return array_merge($this->getMapping(), ['class' => get_called_class()]);
    }

    /**
     * @return []
     */
    protected abstract function getMapping();
}