<?php

namespace Bitmap;

use Chinook\Valid\Inline\Album;
use Chinook\Valid\Inline\Artist;
use Chinook\Valid\Inline\Track;
use Chinook\Valid\Inline\Genre;
use Chinook\Valid\Inline\MediaType;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use PDO;

class EntityTest extends TestCase
{
    const CONNECTION_NAME = 'chinook';

    public static function setUpBeforeClass()
    {
	    if (isset(Logger::getLevels()[strtoupper(getenv('PHPUNIT_LOGGING'))])) {
		    Bitmap::current()->setLogger(new Logger(new StreamHandler(fopen('php://stdout', 'a'), strtoupper(getenv('PHPUNIT_LOGGING')))));
	    }
    }

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
	 * @param string|int $artist
	 * @param array|null $context
	 *
	 * @dataProvider addNewAlbumData
	 */
	public function testAddNewAlbum($artist, $context = null)
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

		$this->assertTrue($album->save($context));
		$this->assertNotNull($album->getId());

		$this->assertEquals(275 + ($artistIsNew ? 1 : 0), Bitmap::connection('chinook')->query("select count(*) as `total` from `Artist`")->fetchAll(PDO::FETCH_ASSOC)[0]['total']);

		$this->assertNotNull($artist->getId());
        $this->assertEquals(348, Bitmap::connection('chinook')->query("select count(*) as `total` from `Album`")->fetchAll(PDO::FETCH_ASSOC)[0]['total']);
	}

	/**
	 * Attempt to add a new song in the database with no declared genre
	 */
	public function testAddNewSong()
	{
		$track = new Track();
		$track->setName("Silly song");
		$track->setAlbum(Album::select()->where('id', '=', 321)->one());
		$track->setBytes(12345);
		$track->setMilliseconds(123000);
		$track->setComposer("Anonymous");
		$track->setUnitPrice(0.01);
		$track->setMedia(MediaType::select()->where('id', '=', 1)->one());

		$track->save(['genre']);
		$this->assertNotNull($track->getId());
		$this->assertNotNull($track->getBitmapHash());
	}

	public function addNewAlbumData()
	{
		return [
			[ 'Radiohead', ['artist']],
            [ 'Radiohead', ['artist' => 1]],
            [ 'Radiohead'],
            [ 193, ['artist']],
            [ 193, ['artist' => 1]],
            [ 193 ]
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
        /** @var Genre $genre */
        $genre = new Genre();
        $genre->setName("Funk");
        /** @var Track $track */
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