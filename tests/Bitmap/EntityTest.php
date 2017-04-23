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

    public function testAddNewArtist()
    {
        $artist = new Artist();
        $artist->name = 'Radiohead';

        $this->assertTrue($artist->save());
        $this->assertNotNull($artist->getId());

        $this->assertEquals(276, Bitmap::connection('chinook')->query("select count(*) as `total` from `Artist`")->fetchAll(PDO::FETCH_ASSOC)[0]['total']);
    }

	/**
	 * @param $with
	 * @param $artistSaved
	 *
	 * @dataProvider addNewAlbumData
	 */
	public function testAddNewAlbum($artist, $with)
	{
        $artistIsNew = false;
        if (is_string($artist)) {
            $name = $artist;
            $artist = new Artist();
            $artist->name = $name;
            $artistIsNew = true;
        } else if (is_int($artist)) {
            $artist = Artist::select()->where('ArtistId', '=', $artist)->one();
        }

		$album = new Album();
		$album->setTitle("OK Computer");
		$album->setArtist($artist);

		$this->assertTrue($album->save($with));
		$this->assertNotNull($album->getId());

		$this->assertEquals(275 + ($artistIsNew ? 1 : 0), Bitmap::connection('chinook')->query("select count(*) as `total` from `Artist`")->fetchAll(PDO::FETCH_ASSOC)[0]['total']);

		$this->assertNotNull($artist->getId());
        $this->assertEquals(348, Bitmap::connection('chinook')->query("select count(*) as `total` from `Album`")->fetchAll(PDO::FETCH_ASSOC)[0]['total']);
	}

	public function addNewAlbumData()
	{
		return [
			[
                'Radiohead',
                ['ArtistId'],
				true
			],
            [
                'Radiohead',
                ['ArtistId' => 1],
                true
            ],
            [
                'Radiohead',
                null,
                true
            ],
            [
                193,
                ['ArtistId'],
                true
            ],
            [
                193,
                ['ArtistId' => 1],
                true
            ],
            [
                193,
                null,
                true
            ]
		];
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