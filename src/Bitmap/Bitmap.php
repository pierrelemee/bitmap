<?php

namespace PierreLemee\Bitmap;

use PDO;

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
     * @var Bitmap
     */
    private static $BITMAP;

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
        $default = $default || sizeof(self::current()->connections) == 0;

        self::current()->connections[$name] = new PDO($dsn);

        if ($default) {
            self::current()->default = self::current()->connections[$name];
        }
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