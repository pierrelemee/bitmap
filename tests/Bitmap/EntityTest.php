<?php

namespace Bitmap;

use Chinook\Album;
use Chinook\Artist;
use PHPUnit\Framework\TestCase;
use Bitmap\Bitmap;
use PDO;

class EntityTest extends TestCase
{
    const CONNECTION_NAME = 'chinook';

    /**
     * @before
     */
    public function before()
    {
        Bitmap::addConnection(self::CONNECTION_NAME, 'sqlite://' . __DIR__ . '/resources/Chinook_Sqlite_AutoIncrementPKs.sqlite');
        Bitmap::connection(self::CONNECTION_NAME)->beginTransaction();
    }

    /**
     * @after
     */
    public function after()
    {
        Bitmap::connection(self::CONNECTION_NAME)->rollBack();
    }

    public function getArtistById()
    {
        $artist = Artist::find(sprintf('select * from `Artist` where id = %d', 94));

        $this->assertNotNull($artist);
        $this->assertSame('Jimi Hendrix', $artist->Name);
    }

    public function testGetArtists()
    {
        $artists = Artist::find('select * from `Artist` where name like "The%"');

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
            $this->assertArrayHasKey($artist->getArtistId(), $expected);
            $this->assertEquals($expected[$artist->getArtistId()], $artist->Name);
        }
    }

    public function testGetAlbumById()
    {
        $album = Album::findOne(sprintf('select * from `Album` where AlbumId = %d', 275));

        $this->assertNotNull($album);
        $this->assertSame('Vivaldi: The Four Seasons', $album->getTitle());
        $this->assertSame(209, $album->getArtistId());
    }

    public function testAddNewArtist()
    {
        $artist = new Artist();
        $artist->Name = 'Radiohead';

        $this->assertTrue($artist->save());

        $this->assertEquals(276, Bitmap::connection('chinook')->query("select count(*) as `total` from `Artist`")->fetchAll(PDO::FETCH_ASSOC)[0]['total']);
    }

    public function testUpdateArtist()
    {
        $artist = Artist::findOne(sprintf("select * from Artist where ArtistId = %d", 179));
        // "Scorptions" in database
        $artist->Name = "The Scorpions";
        $this->assertTrue($artist->save());

        $this->assertEquals(15, Bitmap::connection('chinook')->query('select count(*) as `total` from `Artist` where name like "The%"')->fetchAll(PDO::FETCH_ASSOC)[0]['total']);
        $this->assertEquals(275, Bitmap::connection('chinook')->query('select count(*) as `total` from `Artist`')->fetchAll(PDO::FETCH_ASSOC)[0]['total']);
    }

    public function testDeleteArtist()
    {
        $artist = Artist::findOne(sprintf("select * from Artist where ArtistId = %d", 166));
        // "Avril Lavigne" in database
        $this->assertTrue($artist->delete());

        $this->assertEquals(274, Bitmap::connection('chinook')->query('select count(*) as `total` from `Artist`')->fetchAll(PDO::FETCH_ASSOC)[0]['total']);
    }
}