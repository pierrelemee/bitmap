<?php

namespace Bitmap;


use Chinook\Valid\Inline\Artist;
use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
    const CONNECTION_SQLITE = 'chinook_sqlite';
    const CONNECTION_MYSQL  = 'chinook_mysql';


    public static function setUpBeforeClass()
    {
        foreach (self::connections() as $name => $arguments) {
            Bitmap::addConnection($name, $arguments[0], false, isset($arguments[1]) ? $arguments[1] : null, isset($arguments[2]) ? $arguments[2] : null);
        }
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

    private static function connections()
    {
        return [
            self::CONNECTION_SQLITE => ['sqlite://' . __DIR__ . '/resources/Chinook_Sqlite_AutoIncrementPKs.sqlite'],
            self::CONNECTION_MYSQL => ['mysql://host=localhost;dbname=Chinook;', "root"],
        ];
    }

    public function testGetNoArtist()
    {
        foreach (array_keys(self::connections()) as $connection) {
            $artist = Artist::select()->where('Name', '=', "Justin Bieber")->one($connection);

            $this->assertNull($artist);
        }

    }

    public function testGetArtistById()
    {
        foreach (array_keys(self::connections()) as $connection) {
            //$artist = Artist::query(sprintf('select * from `Artist` where ArtistId = %d', 94))->one();
            $artist = Artist::select()->where('ArtistId', '=', 94)->one($connection);

            $this->assertNotNull($artist);
            $this->assertSame('Jimi Hendrix', $artist->name);
        }
    }


}