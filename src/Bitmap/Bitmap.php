<?php

namespace Bitmap;

use Monolog\Handler\NullHandler;
use Monolog\Logger;
use PDO;
use ReflectionClass;
use Exception;

/**
 * Class Bitmap
 * @package PierreLemee\Bitmap
 */
class Bitmap
{
    /**
     * @var PDO[]
     */
    protected $connections;
    /**
     * @var PDO
     */
    protected $default;
    /**
     * @var Mapper[]
     */
    protected $mappers;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var Bitmap
     */
    private static $BITMAP;

    const TYPE_INTEGER  = "integer";
    const TYPE_BOOLEAN  = "boolean";
    const TYPE_FLOAT    = "float";
    const TYPE_STRING   = "string";
    const TYPE_OBJECT   = "object";
    const TYPE_DATE     = "date";
    const TYPE_DATETIME = "datetime";

    protected $transformers;

    public function __construct($logger = null, $connections = [], $default = null, $mappers = [])
    {
        $this->connections  = $connections;
        $this->default      = $default;
        $this->mappers      = $mappers;
        $this->transformers = [];
        $this->logger = $logger ? : new Logger('bitmap', [new NullHandler(Logger::CRITICAL)]);

        $dir = realpath(__DIR__ . '/Transformers');
        foreach (scandir($dir) as $file) {
            if (preg_match("/\\.php$/", $file)) {
                $reflection = new ReflectionClass(__NAMESPACE__ . '\\Transformers\\' . preg_replace("/\\.php$/", "", $file));
                
                if ($reflection->isSubclassOf(Transformer::class)) {
                    $transformer = $reflection->newInstance();
                    $this->transformers[$transformer->getName()] = $transformer;
                }
            }
        }

        self::$BITMAP = $this;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Singleton accessor, with on-the-fly initialization
     *
     * @param array|null $connections
     * @param boolean|null $default
     *
     * @return Bitmap
     */
    public static function current($connections = null, $default = null)
    {
        if (null === self::$BITMAP) {
            self::$BITMAP = $connections ? new Bitmap(null, $connections, $default) : new Bitmap();
        }

        return self::$BITMAP;
    }

    /**
     * Declares a PDO connection by its name and DSN
     *
     * @param string $name
     * @param string $dsn
     * @param boolean $default
     * @param string $user
     * @param string $password
     *
     * @return void
     */
    public function addConnection($name, $dsn, $default = true, $user = null, $password = null)
    {
        if (!isset(self::current()->connections[$name])) {
            $default = $default || sizeof(self::current()->connections) == 0;

            self::current()->connections[$name] = new PDO($dsn, $user, $password);

            if ($default) {
                self::current()->default = self::current()->connections[$name];
            }
        }
    }

    /**
     * @param Mapper $mapper
     */
    public function addMapper(Mapper $mapper)
    {
        $this->mappers[$mapper->getClass()] = $mapper;
    }

    /**
     * Checks whether mapper for class $class has been defined yet
     *
     * @param string $class
     *
     * @return boolean
     */
    public function hasMapper($class)
    {
        return isset($this->mappers[$class]);
    }

    /**
     * Retrieves mapper defined for class $class
     *
     * @param string $class
     *
     * @return Mapper
     *
     * @throws Exception
     */
    public function getMapper($class)
    {
        if (!$this->hasMapper($class)) {
            $entity = new ReflectionClass($class);
            if ($entity->isSubclassOf(Entity::class)) {
                $this->addMapper($entity->newInstance()->getMapper());
            } else {
                throw new Exception(sprintf("'%s' must be a sub class of '%s'", $class, Entity::class));
            }
        }

        return $this->mappers[$class];
    }

    /**
     *
     *
     * @param Transformer $transformer
     */
    public function addTransformer(Transformer $transformer)
    {
        $this->transformers[$transformer->getName()] = $transformer;
    }

    public function hasTransformer($name)
    {
        return isset($this->transformers[$name]);
    }

    /**
     * @param string $type
     *
     * @return Transformer
     */
    public function getTransformer($type)
    {
        if (self::hasTransformer($type)) {
            return $this->transformers[$type];
        }

        return $this->transformers[self::TYPE_STRING];
    }

    /**
     * Fetches for a PDO connection by its name, or the default connection if no name provided
     *
     * @param string $name
     *
     * @return null|PDO
     */
    public function connection($name = null)
    {
        if (null !== $name) {
            return isset($this->connections[$name]) ? $this->connections[$name] : null;
        }

        return $this->default;
    }

}