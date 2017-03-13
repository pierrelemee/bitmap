<?php

namespace Bitmap\Mappers;

use Bitmap\Exceptions\MapperException;
use Bitmap\Fields\MethodField;
use Bitmap\Fields\PropertyField;
use Bitmap\Mapper;
use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;

class ArrayMapper extends Mapper
{
    protected $reflection;
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

    public function __construct(array $config)
    {
        if (!isset($mapping['class'])) {
            throw new MapperException("Missing 'class' key in mapping array");
        }
        parent::__construct($mapping['class'], self::value($config, 'table'));
        $this->reflection = new ReflectionClass($this->class);
        if(null === $primary = self::value($config, 'primary')) {
            throw new MapperException("No 'primary' defined", $this->class);
        }
        if (is_array($primary)) {

        }

    }

    private function field($name, array $data)
    {
        if (isset($data['property'])) {
            
        } else if (isset($data['getter'])) {
            return isset($data['setter']) ?
                MethodField::fromMethod($name, $this->getReflectionMethod($data['getter']), self::value($data, 'column'))
                :
                MethodField::fromMethods($name, $this->getReflectionMethod($data['getter']), $this->getReflectionMethod($data['setter']), self::value($data, 'column'))
            ;
        } else {
            throw new MapperException("Unable to create field '{$name}', one of these keys must be declared: ['property', 'getter']");
        }
    }

    private function getReflectionProperty($config)
    {
        if (is_object($config) && $config instanceof ReflectionProperty) {
            $field = new PropertyField($config, self::value($data, 'column'));
        } else if (is_string($config)) {
            if ($this->reflection->hasProperty($config)) {
                $field = new PropertyField($this->reflection->getProperty($config), self::value($data, 'column'));
            } else {
                throw new MapperException("");
            }
        } else {
            throw new MapperException("");
        }
    }

    private function getReflectionMethod($config)
    {
        if (is_object($config) && $config instanceof ReflectionMethod) {
            return $config;
        }
        if (is_string($config)) {
            if ($this->reflection->hasMethod($config)) {
                return $this->reflection->getMethod($config);
            }
            throw new MapperException("");
        }
        throw new MapperException("");
    }

    /**
     * @param array $data
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    private static function value(array $data, $name, $default = null)
    {
        return isset($data[$name]) ? $data[$name] : $default;
    }
}