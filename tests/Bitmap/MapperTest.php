<?php

namespace Bitmap;

use Bitmap\Associations\MethodAssociationOne;
use Bitmap\Associations\PropertyAssociationOne;
use Misc\Car;
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
            [MethodAssociationOne::class, 'car', Car::class, true],
            [MethodAssociationOne::class, 'sister', Car::class, false],
            [MethodAssociationOne::class, 'nephew', Car::class, false],
            [MethodAssociationOne::class, 'nephew', Car::class, true, 'getNephew', 'addNephew']
        ];
    }
}