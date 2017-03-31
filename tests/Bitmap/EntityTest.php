<?php

namespace Bitmap;

use Chinook\Valid\Inline\Album;
use Chinook\Valid\Inline\Artist;
use Chinook\Valid\Inline\Track;
use Chinook\Valid\Inline\Genre;
use Chinook\Valid\Inline\MediaType;
use PHPUnit\Framework\TestCase;
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

	public function testGetNoArtist()
	{
		$artist = Artist::select()->where('Name', '=', "Justin Bieber")->one();

		$this->assertNull($artist);
	}

    public function testGetArtistById()
    {
        //$artist = Artist::query(sprintf('select * from `Artist` where ArtistId = %d', 94))->one();
        $artist = Artist::select()->where('ArtistId', '=', 94)->one();

        $this->assertNotNull($artist);
        $this->assertSame('Jimi Hendrix', $artist->name);
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

    public function testAddNewArtist()
    {
        $artist = new Artist();
        $artist->name = 'Radiohead';

        $this->assertTrue($artist->save());
        $this->assertNotNull($artist->getId());

        $this->assertEquals(276, Bitmap::connection('chinook')->query("select count(*) as `total` from `Artist`")->fetchAll(PDO::FETCH_ASSOC)[0]['total']);
    }

    public function testAddNewTrackAndGenre()
    {
        $genre = new Genre();
        $genre->setName("Funk");
        $track = new Track();
        $track->setName("B.Y.O.B. (funk version)");
        $track->setUnitPrice(0.99);
        $track->setBytes(4365405);
        $track->setComposer("Tankian, Serj");
        $track->setMilliseconds(217886);
        $track->setGenre($genre);
        $track->setMedia(MediaType::select()->where("MediaTypeId", "=", 1)->one());

        $this->assertTrue($track->save());
        $this->assertNotNull($track->getId());
        $this->assertNotNull($track->getGenre()->getId());

        $this->assertEquals(26, Bitmap::connection('chinook')->query("select count(*) as `total` from `Genre`")->fetchAll(PDO::FETCH_ASSOC)[0]['total']);
    }

    public function testUpdateArtist()
    {
        $artist = Artist::select()->where('ArtistId', '=', 179)->one();
        // "Scorpions" in database
        $artist->name = "The Scorpions";
        $this->assertTrue($artist->save());

        $this->assertEquals(15, Bitmap::connection('chinook')->query('select count(*) as `total` from `Artist` where name like "The%"')->fetchAll(PDO::FETCH_ASSOC)[0]['total']);
        $this->assertEquals(275, Bitmap::connection('chinook')->query('select count(*) as `total` from `Artist`')->fetchAll(PDO::FETCH_ASSOC)[0]['total']);
    }

    public function testUpdateTrackWithNewGenre()
    {
        $genre = new Genre();
        $genre->setName("Funk");
        $track = Track::select()->where("TrackId", "=", 2555)->one();
        $track->setGenre($genre);

        $this->assertTrue($track->save());
        $this->assertNotNull($track->getGenre()->getId());

        $this->assertEquals(26, Bitmap::connection('chinook')->query("select count(*) as `total` from `Genre`")->fetchAll(PDO::FETCH_ASSOC)[0]['total']);
    }

    public function testDeleteArtist()
    {
        $artist = Artist::select()->where('ArtistId', '=', 166)->one();
        // "Avril Lavigne" in database
        $this->assertTrue($artist->delete());

        $this->assertEquals(274, Bitmap::connection('chinook')->query('select count(*) as `total` from `Artist`')->fetchAll(PDO::FETCH_ASSOC)[0]['total']);
    }
}