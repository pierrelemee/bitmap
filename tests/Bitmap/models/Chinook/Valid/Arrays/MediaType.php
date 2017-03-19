<?php

namespace Chinook\Valid\Arrays;

use Bitmap\ArrayMappedEntity;

class MediaType extends ArrayMappedEntity
{
    protected $id;
    protected $name;

    protected function getMapping()
    {
        return [
            'primary' => [
                'column' => 'MediaTypeId',
                'type' => 'int',
                'getter' => 'getId'
            ],
            'fields' => [
                'name' => [
                    'column' => 'Name',
                    'type' => 'string',
                    'getter' => 'getName'
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}