<?php

namespace Bitmap;

use Bitmap\Associations\MethodAssociationOne;
use Bitmap\Associations\MethodAssociationOneToMany;
use Bitmap\Associations\PropertyAssociationOne;
use Bitmap\Associations\PropertyAssociationOneToMany;
use Misc\Transport;
use Misc\User;
use PHPUnit\Framework\TestCase;
use Exception;

class MapperTest extends TestCase
{
    /**
     * @param $associationClass
     * @param $name
     * @param $class
     * @param bool $success
     * @param string $getter
     * @param string $setter
     *
     * @dataProvider dataPropertyAssociationOne
     */
    public function testAssociationOne($associationClass, $name, $class, $success = true, $setter = null, $getter = null)
    {
        try {
            $mapper = new Mapper(User::class);
            $mapper->addAssociationOne($name, $class, $name, $getter, $setter);

            $this->assertEquals(true, $success);

            if ($success) {
                $this->assertEquals(1, sizeof($mapper->associations()));
                $this->assertInstanceOf($associationClass, $mapper->getAssociation($name));
            }
        } catch (Exception $e) {
            if ($success) {
                $this->fail("Unexpected exception ('{$e->getMessage()}')");
            }
        }
    }

    public function dataPropertyAssociationOne()
    {
        return [
            [PropertyAssociationOne::class, 'parent', User::class],
            [PropertyAssociationOne::class, 'brother', User::class, false],
            [MethodAssociationOne::class, 'car', Transport::class, true],
            [MethodAssociationOne::class, 'sister', Transport::class, false],
            [MethodAssociationOne::class, 'nephew', Transport::class, false],
            [MethodAssociationOne::class, 'nephew', Transport::class, true, 'getNephew', 'addNephew']
        ];
    }

    /**
     * @param $associationClass
     * @param $name
     * @param $class
     * @param bool $success
     * @param string $getter
     * @param string $setter
     *
     * @dataProvider dataPropertyAssociationOneToMany
     */
    public function testAssociationOneToMany($associationClass, $name, $class, $success = true, $setter = null, $getter = null)
    {
        try {
            $mapper = new Mapper(User::class);
            $mapper->addAssociationOneToMany($name, $class, $name, $getter, $setter);

            $this->assertEquals(true, $success);

            if ($success) {
                $this->assertEquals(1, sizeof($mapper->associations()));
                $this->assertInstanceOf($associationClass, $mapper->getAssociation($name));
            }
        } catch (Exception $e) {
            if ($success) {
                $this->fail("Unexpected exception ('{$e->getMessage()}')");
            }
        }
    }

    public function dataPropertyAssociationOneToMany()
    {
        return [
            [PropertyAssociationOneToMany::class, 'uncles', User::class],
            [MethodAssociationOneToMany::class, 'aunts', Transport::class, false],
            [MethodAssociationOneToMany::class, 'cousins', Transport::class],
            [MethodAssociationOneToMany::class, 'children', Transport::class, false],
            [MethodAssociationOneToMany::class, 'children', Transport::class, true, 'getChildren', 'addChildren']
        ];
    }
}