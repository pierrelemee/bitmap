<?php

namespace Bitmap;

use PDO;
use Monolog\Logger;

class BitmapBuilder
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

    public function addConnection($name, PDO $connection, $default)
    {
        $this->connections[$name] = $connection;

        if ($default) {
            $this->default = $default;
        }

        return $this;
    }

    public function build()
    {
        return new Bitmap();
    }
}