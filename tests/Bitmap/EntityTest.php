<?php

namespace Bitmap\Tests;

use Bitmap\Bitmap;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use PDO;

abstract class EntityTest extends TestCase
{
    const CONNECTION_SQLITE = 'chinook_sqlite';
    const CONNECTION_MYSQL  = 'chinook_mysql';

    static $CONNECTIONS = [
        self::CONNECTION_SQLITE => ['sqlite://' . __DIR__ . '/resources/Chinook_Sqlite_AutoIncrementPKs.sqlite'],
        self::CONNECTION_MYSQL => ['mysql://host=localhost;dbname=Chinook;', "root"],
    ];

    public static function setUpBeforeClass()
    {
        if (isset(Logger::getLevels()[strtoupper(getenv('PHPUNIT_LOGGING'))])) {
            Bitmap::current()->setLogger(new Logger(new StreamHandler(fopen('php://stdout', 'a'), strtoupper(getenv('PHPUNIT_LOGGING')))));
        }

        foreach (self::$CONNECTIONS as $name => $arguments) {
            Bitmap::addConnection($name, $arguments[0], false, isset($arguments[1]) ? $arguments[1] : null, isset($arguments[2]) ? $arguments[2] : null);
        }
    }

    protected function connections()
    {
        return self::$CONNECTIONS;
    }

    /**
     * @before
     */
    public function before()
    {
        foreach (self::connections() as $name => $arguments) {
            Bitmap::connection($name)->beginTransaction();
        }
    }

    /**
     * @after
     */
    public function after()
    {
        foreach (self::connections() as $name => $arguments) {
            Bitmap::connection($name)->rollBack();
        }
    }

    protected function queryOne($connection, $sql)
    {
        $statement = Bitmap::connection($connection)->query($sql);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    protected function queryAll($connection, $sql)
    {
        $statement = Bitmap::connection($connection)->query($sql);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function getCountArtists($connection, $where = [])
    {
        return $this->queryOne($connection, 'select count(*) as `total` from `Artist`' . (count($where) > 0 ? " where " . implode(" and ", $where) : ''))['total'];
    }

    protected function getCountAlbums($connection)
    {
        return $this->queryOne($connection, 'select count(*) as `total` from `Album`')['total'];
    }

    protected function getCountTracks($connection)
    {
        return $this->queryOne($connection, 'select count(*) as `total` from `Track`')['total'];
    }

    protected function getCountGenres($connection)
    {
        return $this->queryOne($connection, 'select count(*) as `total` from `Genre`')['total'];
    }
}