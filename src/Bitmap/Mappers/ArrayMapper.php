<?php

namespace Bitmap\Mappers;

use Bitmap\Association;
use Bitmap\Associations\MethodAssociationMultiple;
use Bitmap\Associations\MethodAssociationOne;
use Bitmap\Associations\PropertyAssociationMultiple;
use Bitmap\Associations\PropertyAssociationOne;
use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\Exceptions\MapperException;
use Bitmap\Field;
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
                ],
                // Property
                'property' => 'tracks',
                // Method
                'getter' => 'getTracks',
                'setter' => 'setTracks'
            ]
        ]
    ];

    public function __construct(array $config)
    {
        if (!isset($config['class'])) {
            throw new MapperException("Missing 'class' key in mapping array");
        }
        parent::__construct($config['class'], self::value($config, 'table'));
        $this->reflection = new ReflectionClass($this->class);
        if(null === $primary = self::value($config, 'primary')) {
            throw new MapperException("No 'primary' defined", $this->class);
        }
        $this->addField($this->field('primary', $primary), true);

        foreach (self::value($config, 'fields', []) as $name => $field) {
            $this->addField($this->field($name, $field));
        }

        foreach (self::value($config, 'associations', []) as $name => $association) {
            $this->addAssociation($this->association($name, $association));
        }

    }

    /**
     * @param $name
     * @param array $data
     *
     * @return Field
     *
     * @throws MapperException
     */
    private function field($name, array $data)
    {
        if (isset($data['property'])) {
            $field = new PropertyField($this->getReflectionProperty($data['property']), self::value($data, 'column'));
        } else if (isset($data['getter'])) {
            $field = isset($data['setter']) ?
                MethodField::fromMethods($name, $this->getReflectionMethod($data['getter']), $this->getReflectionMethod($data['setter']), self::value($data, 'column'))
                :
                MethodField::fromMethod($name, $this->getReflectionMethod($data['getter']), self::value($data, 'column'))
            ;
        } else {
            throw new MapperException("Unable to create field '{$name}', one of these keys must be declared: ['property', 'getter']");
        }

        return $field->setTransformer(Bitmap::getTransformer(self::value($data, 'transformer', Bitmap::TYPE_STRING)))
            ->setNullable(self::value($data, 'nullable', true))
            ->setIncremented(self::value($data, 'incremented', false));
    }

    private function getReflectionProperty($config)
    {
        if (is_object($config) && $config instanceof ReflectionProperty) {
            return $config;
        } else if (is_string($config)) {
            if ($this->reflection->hasProperty($config)) {
                return $this->reflection->getProperty($config);
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
     * @param $name
     * @param array $data
     *
     * @return Association
     *
     * @throws MapperException
     */
    private function association($name, array $data)
    {
        if (null === $class = self::value($data, 'class', null)) {
            throw new MapperException("No class defined for association '{$name}'", $this->reflection->name);
        }
        $mapper = Entity::mapper($class);

        if (null === $type = self::value($data, 'type', null)) {
            throw new MapperException("No type defined for association '{$name}'", $this->reflection->name);
        }


        if (isset($data['property'])) {
            $property = $this->getReflectionProperty($data['property']);

            switch (strtolower($type)) {
                case 'one':
                    return new PropertyAssociationOne($name, $mapper, $property, isset($data['options']) ? self::value($data['options'], 'target', null) : null);
                case 'multiple':
                    return new PropertyAssociationMultiple($name, $mapper, $property, isset($data['options']) ? self::value($data['options'], 'target', null) : null);
                default:
                    throw new MapperException("Undefined type for association '{$name}'", $this->reflection->name);
            }
        } else if (isset($data['getter'])) {
            $getter = $this->getReflectionMethod($data['getter']);
            $setter = isset($data['setter']) ? $this->getReflectionMethod($data['setter']) : MethodField::setterForGetter($getter);

            switch (strtolower($type)) {
                case 'one':
                    return new MethodAssociationOne($name, $mapper, $getter, $setter, isset($data['options']) ? self::value($data['options'], 'target', null)  : null);
                case 'multiple':
                    return new MethodAssociationMultiple($name, $mapper, $getter, $setter, isset($data['options']) ? self::value($data['options'], 'target', null)  : null);
                default:
                    throw new MapperException("Undefined type for association '{$name}'", $this->reflection->name);
            }
        } else {
            throw new MapperException("Unable to create association '{$name}' with type '{$type}', one of these keys must be declared: ['property', 'getter']");
        }
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