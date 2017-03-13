<?php

namespace Bitmap;

use Exception;

abstract class ArrayMappedEntity extends Entity
{
    protected $example = [
        'class' => __CLASS__,
        'table' => "Table",
        'primary' => [

        ],
        'fields' => [
            'id' => [
                'type' => 'int',
                'incremented' => true,
                'nullable' => false,
                'column' => 'AlbumId',
                // Property
                'property' => 'id',
                // Method
                'getter' => 'getId',
                'setter' => 'setId'
            ]
        ],
        'associations' => [
            'artist' => [
                'class' => 'Chinook\Artist',
                'type' => 'one',
                'options' => [
                    'column' => 'ArtistId'
                ]
            ],
            'tracks' => [
                'class' => 'Chinook\Artist',
                'type' => 'one',
                'options' => [
                    'column' => 'ArtistId'
                ]
            ]
        ]
    ];

    public function getMapper()
    {
        $class = get_called_class();
        $mapping = array_merge($this->getMapping(), ['class' => $class]);

        if (isset($mapping['class'])) {
            $mapper = new Mapper($mapping['class'], isset($mapping['table']) ? $mapping['table'] : null;

            if(isset($mapping['fields'])) {
                foreach ($mapping['fields'] as $name => $field) {
                    if (is_array($field)) {

                    }
                    else if (is_object($field)) {
                        if ($field instanceof Field) {
                            $mapper->addField($field);
                        } else {
                            throw new Exception("Field with name '{$name}' is not a subclass of 'Bitmap\\Field'");
                        }
                    } else {
                        throw new Exception("Field with name '{$name}' must be either an array or a subclass of 'Bitmap\\Field'");
                    }
                }
            } else {
                throw new Exception("Missing 'fields' key in mapping array");
            }



            return $mapper;
        }

        throw new Exception("Missing 'class' key in mapping array");
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