<?php

namespace Bitmap;

use Chinook\Valid\Inline\Album;
use Chinook\Valid\Inline\Artist;
use Chinook\Valid\Inline\Employee;
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

    public function testGetAlbumsByArtist()
    {
        $albums = Album::select()->where('ArtistId', '=', 131)->all();

        $this->assertEquals(2, sizeof($albums));
    }

    public function testGetArtists()
    {
        $artists = Artist::select()->where('Name', 'like', 'The%')->all();

        $expected = [
            137 => 'The Black Crowes',
            138 => 'The Clash',
            139 => 'The Cult',
            140 => 'The Doors',
            141 => 'The Police',
            142 => 'The Rolling Stones',
            143 => 'The Tea Party',
            144 => 'The Who',
            156 => 'The Office',
            174 => 'The Postal Service',
            176 => 'The Flaming Lips',
            200 => 'The Posies',
            247 => 'The King\'s Singers',
            259 => 'The 12 Cellists of The Berlin Philharmonic'
        ];

        $this->assertSameSize($expected, $artists);

        foreach ($artists as $artist) {
            $this->assertArrayHasKey($artist->getId(), $expected);
            $this->assertEquals($expected[$artist->getId()], $artist->name);
        }
    }

    public function testGetAlbumById()
    {
        $album = Album::select()->where('AlbumId', '=', 275)->one();

        $this->assertNotNull($album);
        $this->assertSame('Vivaldi: The Four Seasons', $album->getTitle());
        $this->assertSame(209, $album->getArtist()->getId());
    }

    public function testGetEmployeeAndSuperior()
    {
        $employee= Employee::select()->where('EmployeeId', '=', 8)->one();

        $this->assertNotNull($employee);
        $this->assertNotNull($employee->getSuperior());
        $this->assertEquals(Employee::class, get_class($employee->getSuperior()));
    }

}