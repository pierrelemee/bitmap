<?php

namespace Tests\Bitmap;

use Bitmap\Bitmap;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use PDO;

abstract class EntityTest extends TestCase
{
    const CONNECTION_SQLITE = 'chinook_sqlite';
    const CONNECTION_MYSQL  = 'chinook_mysql';

    public static function setUpBeforeClass()
    {

        if (isset(Logger::getLevels()[strtoupper(getenv('PHPUNIT_LOGGING'))])) {
            Bitmap::current()->setLogger(new Logger(new StreamHandler(fopen('php://stdout', 'a'), strtoupper(getenv('PHPUNIT_LOGGING')))));
        }

        foreach (self::connections() as $name => $arguments) {
            Bitmap::current()->addConnection($name, $arguments[0], false, isset($arguments[1]) ? $arguments[1] : null, isset($arguments[2]) ? $arguments[2] : null);
        }
    }

    /**
     * @before
     */
    public function before()
    {
        foreach (self::connections() as $name => $arguments) {
            Bitmap::current()->connection($name)->beginTransaction();
        }
    }

    /**
     * @after
     */
    public function after()
    {
        foreach (self::connections() as $name => $arguments) {
            Bitmap::current()->connection($name)->rollBack();
        }
    }

    private static function connections()
    {
        return [
            self::CONNECTION_SQLITE => ['sqlite://' . __DIR__ . '/resources/Chinook_Sqlite_AutoIncrementPKs.sqlite'],
            self::CONNECTION_MYSQL  => ['mysql://host=localhost;dbname=Chinook;', "root"],
        ];
    }

    protected function getConnectionNames()
    {
        return [self::CONNECTION_MYSQL, self::CONNECTION_SQLITE];
    }

    /**
     * @param $data array
     *
     * @return array
     */
    protected function data(array $data)
    {
        return array_map(function ($line) {
            return array_map(function ($connection) use ($line) {
                return array_merge([$connection], $line);
            }, $this->getConnectionNames());
        }, $data);
    }

    protected function queryValue($connection, $sql, $default = null)
    {
        $result = Bitmap::current()->connection($connection)->query($sql)->fetch(PDO::FETCH_NUM);

        return count($result) > 0 ? $result[0] : $default;
    }

    protected function queryCount($connection, $table)
    {
        return $this->queryValue($connection, "select count(*) from $table", 0);
    }
}