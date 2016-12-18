<?php

namespace PierreLemee\Bitmap;

use PierreLemee\Bitmap\Transformers\IntegerTransformer;
use PierreLemee\Bitmap\Transformers\StringTransformer;
use PDO;

/**
 * Class Bitmap
 * @package PierreLemee\Bitmap
 *
 * TODO:
 *  - add unit tests
 *  - class annotations mapper
 *  - field alias
 *  - custom setter
 *  - add save method
 *  - ono-to-one association
 */
class Bitmap
{
    /**
     * @var PDO[]
     */
    protected $connections = [];
    /**
     * @var PDO
     */
    protected $default;
    /**
     * @var Mapper[]
     */
    protected $mappers = [];
    /**
     * @var Bitmap
     */
    private static $BITMAP;

    const TYPE_INTEGER = "int";
    const TYPE_FLOAT = "float";
    const TYPE_STRING = "string";
    const TYPE_OBJECT = "object";

    protected $transformers = [];

    public function __construct()
    {
        foreach ([new IntegerTransformer(), new StringTransformer()] as $transformer) {
            $this->transformers[$transformer->getName()] = $transformer;
        }
    }

    /**
     * Singleton accessor, with on-the-fly initialization
     *
     * @return Bitmap
     */
    public static function current()
    {
        if (null === self::$BITMAP) {
            self::$BITMAP = new Bitmap();
        }

        return self::$BITMAP;
    }

    /**
     * Declares a PDO connection by its name and DSN
     *
     * @param string $name
     * @param string $dsn
     * @param boolean $default
     *
     * @return void
     */
    public static function addConnection($name, $dsn, $default = true)
    {
        if (!isset(self::current()->connections[$name])) {
            $default = $default || sizeof(self::current()->connections) == 0;

            self::current()->connections[$name] = new PDO($dsn);

            if ($default) {
                self::current()->default = self::current()->connections[$name];
            }
        }
    }

    /**
     * @param Mapper $mapper
     */
    public static function addMapper(Mapper $mapper)
    {
        self::current()->mappers[$mapper->getClass()] = $mapper;
    }

    /**
     * Checks whether mapper for class $class has been defined yet
     *
     * @param string $class
     *
     * @return boolean
     */
    public static function hasMapper($class)
    {
        return isset(self::current()->mappers[$class]);
    }

    /**
     * Retrieves mapper defined for class $class
     *
     * @param string $class
     *
     * @return Mapper
     */
    public static function getMapper($class)
    {
        return self::current()->mappers[$class];
    }

    /**
     *
     *
     * @param Transformer $transformer
     */
    public static function addTransformer(Transformer $transformer)
    {
        self::current()->transformers[$transformer->getName()] = $transformer;
    }

    public static function hasTransformer($name)
    {
        return isset(self::current()->transformers[$name]);
    }

    /**
     * @param $name
     * @return Transformer
     */
    public static function getTransformer($name)
    {
        return self::hasTransformer($name) ?
            self::current()->transformers[$name] :
            self::current()->transformers[self::TYPE_STRING];
    }

    /**
     * Fetches for a PDO connection by its name, or the default connection if no name provided
     *
     * @param string $name
     *
     * @return null|PDO
     */
    public static function connection($name = null)
    {
        if (null !== $name) {
            return isset(self::current()->connections[$name]) ? self::current()->connections[$name] : null;
        }

        return self::current()->default;
    }

}