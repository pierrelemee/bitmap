<?php

namespace Tests\Bitmap;

use Bitmap\Bitmap;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use PDO;

abstract class EntityTest extends TestCase
{
    const CONNECTION_SQLITE     = 'chinook_sqlite';
    const CONNECTION_MYSQL      = 'chinook_mysql';
    const CONNECTION_POSTGRESQL = 'chinook_postgresql';

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
            self::CONNECTION_SQLITE     => ['sqlite://' . __DIR__ . '/resources/Chinook_Sqlite_AutoIncrementPKs.sqlite'],
            self::CONNECTION_MYSQL      => ['mysql://host=localhost;dbname=Chinook;', "root"],
            self::CONNECTION_POSTGRESQL => ['pgsql:host=localhost;dbname=Chinook', "postgres"],
        ];
    }

    protected function getConnectionNames()
    {
        return [self::CONNECTION_MYSQL, self::CONNECTION_SQLITE, self::CONNECTION_POSTGRESQL];
    }

    /**
     * @param $lines array
     *
     * @return array
     */
    public function data(array $lines)
    {
        $data = [];

        foreach ($this->getConnectionNames() as $connection) {
            foreach ($lines as $line) {
                $data[] = array_merge([$connection], $line);
            }
        }

        return $data;
    }

    public function dataClasses(array $classes, array $lines = [[]])
    {
        $data = [];

        foreach ($classes as $class) {
            foreach ($this->data($lines) as $line) {
                $data[] = array_merge(is_array($class) ? $class : [$class], $line);
            }
        }

        return $data;
    }

    protected function queryValue($connection, $sql, $default = null)
    {
        $result = Bitmap::current()->connection($connection)->query($sql)->fetch(PDO::FETCH_NUM);

        return count($result) > 0 ? $result[0] : $default;
    }

    protected function queryCount($connection, $table)
    {
        $connection = Bitmap::current()->connection($connection);
        $table = $connection->getAttribute(PDO::ATTR_DRIVER_NAME) === 'pgsql' ? sprintf('"%s"', $table) : $table;

        $result = $connection->query("select count(*) from $table")->fetch(PDO::FETCH_NUM);

        return count($result) > 0 ? $result[0] : $default;
    }
}