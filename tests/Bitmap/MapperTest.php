<?php

namespace Bitmap;

use Bitmap\Associations\MethodAssociationManyToMany;
use Bitmap\Associations\MethodAssociationOne;
use Bitmap\Associations\MethodAssociationOneToMany;
use Bitmap\Associations\PropertyAssociationManyToMany;
use Bitmap\Associations\PropertyAssociationOne;
use Bitmap\Associations\PropertyAssociationOneToMany;
use Misc\Transport;
use Misc\User;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Bitmap\Fields\PropertyField;

class MapperTest extends TestCase
{
    /**
     * @param $associationClass
     * @param $name
     * @param $class
     * @param string $getter
     * @param string $setter
     *
     * @dataProvider dataPropertyAssociationOneSuccess
     */
    public function testAssociationOneSuccess($associationClass, $name, $class, $setter = null, $getter = null)
    {
        $mapper = new Mapper(User::class);
        $reflection = new ReflectionClass(User::class);
        $mapper->addPrimary(new PropertyField('id', $reflection->getProperty('id')));
        $result = $mapper->addAssociationOne($name, $class, $name, $getter, $setter);

        $this->assertInstanceOf(Mapper::class, $result);

        $this->assertTrue($mapper->hasAssociation($name));
        $this->assertInstanceOf($associationClass, $mapper->getAssociation($name));
    }

    public function dataPropertyAssociationOneSuccess()
    {
        return [
            [PropertyAssociationOne::class, 'parent', User::class],
            [MethodAssociationOne::class, 'car', Transport::class],
            [MethodAssociationOne::class, 'nephew', User::class, 'getNephew', 'addNephew']
        ];
    }

    /**
     * @param $name
     * @param $class
     * @param string $getter
     * @param string $setter
     *
     * @dataProvider dataPropertyAssociationOneFailure
     *
     * @expectedException Bitmap\Exceptions\MapperException
     */
    public function testAssociationOneFailure($name, $class, $setter = null, $getter = null)
    {
        $mapper = new Mapper(User::class);
        $reflection = new ReflectionClass(User::class);
        $mapper->addPrimary(new PropertyField('id', $reflection->getProperty('id')));
        $mapper->addAssociationOne($name, $class, $name, $getter, $setter);
    }

    public function dataPropertyAssociationOneFailure()
    {
        return [
            ['sister', Transport::class, false],
            ['nephew', Transport::class, false],
            ['brother', User::class, false],
        ];
    }

    /**
     * @param $associationClass
     * @param $name
     * @param $class
     * @param string $getter
     * @param string $setter
     *
     * @dataProvider dataPropertyAssociationOneToManySuccess
     */
    public function testAssociationOneToManySuccess($associationClass, $name, $class, $setter = null, $getter = null)
    {
        $mapper = new Mapper(User::class);
        $reflection = new ReflectionClass(User::class);
        $mapper->addPrimary(new PropertyField('id', $reflection->getProperty('id')));
        $result = $mapper->addAssociationOneToMany($name, $class, $name, $getter, $setter);

        $this->assertInstanceOf(Mapper::class, $result);

        $this->assertTrue($mapper->hasAssociation($name));
        $this->assertInstanceOf($associationClass, $mapper->getAssociation($name));
    }

    public function dataPropertyAssociationOneToManySuccess()
    {
        return [
            [PropertyAssociationOneToMany::class, 'uncles', User::class],
            [MethodAssociationOneToMany::class, 'cousins', Transport::class],
            [MethodAssociationOneToMany::class, 'children', Transport::class, 'getChildren', 'addChildren']
        ];
    }

    /**
     * @param $name
     * @param $class
     * @param string $getter
     * @param string $setter
     *
     * @dataProvider dataPropertyAssociationOneToManyFailure
     * @expectedException Bitmap\Exceptions\MapperException
     */
    public function testAssociationOneToManyFailure($name, $class, $setter = null, $getter = null)
    {
        $mapper = new Mapper(User::class);
        $reflection = new ReflectionClass(User::class);
        $mapper->addPrimary(new PropertyField('id', $reflection->getProperty('id')));
        $mapper->addAssociationOneToMany($name, $class, $name, $getter, $setter);
    }

    public function dataPropertyAssociationOneToManyFailure()
    {
        return [
            ['aunts', Transport::class],
            ['children', Transport::class]
        ];
    }

    /**
     * @param $associationClass
     * @param $name
     * @param $class
     * @param $via
     * @param null $viaSourceColumn
     * @param null $viaTargetColumn
     * @param null $getter
     * @param null $setter
     *
     * @dataProvider dataPropertyAssociationManyToManySuccess
     */
    public function testAssociationManyToManySuccess($associationClass, $name, $class, $via, $viaSourceColumn = null, $viaTargetColumn = null, $getter = null, $setter = null)
    {
        $mapper = new Mapper(User::class);
        $reflection = new ReflectionClass(User::class);
        $mapper->addPrimary(new PropertyField('id', $reflection->getProperty('id')));
        $result = $mapper->addAssociationManyToMany($name, $class, $via, $viaSourceColumn, $viaTargetColumn, $getter, $setter);

        $this->assertInstanceOf(Mapper::class, $result);

        $this->assertTrue($mapper->hasAssociation($name));
        $this->assertInstanceOf($associationClass, $mapper->getAssociation($name));
    }

    public function dataPropertyAssociationManyToManySuccess()
    {
        return [
            [PropertyAssociationManyToMany::class, 'bikes', Transport::class, 'Bike'],
            [MethodAssociationManyToMany::class, 'skateboards', Transport::class, 'Skateboard', null, null, 'getSkateboards', 'addSkateboards']
        ];
    }

    /**
     * @param $name
     * @param $class
     * @param $via
     * @param null $viaSourceColumn
     * @param null $viaTargetColumn
     * @param null $getter
     * @param null $setter
     *
     * @dataProvider dataPropertyAssociationManyToManyFailure
     * @expectedException Bitmap\Exceptions\MapperException
     */
    public function testAssociationManyToManyFailure($name, $class, $via)
    {
        $mapper = new Mapper(User::class);
        $reflection = new ReflectionClass(User::class);
        $mapper->addPrimary(new PropertyField('id', $reflection->getProperty('id')));
        $mapper->addAssociationManyToMany($name, $class, $via);
    }

    public function dataPropertyAssociationManyToManyFailure()
    {
        return [
            ['skateboards', Transport::class, 'Skateboard'],
        ];
    }
}