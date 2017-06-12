<?php

namespace Bitmap\Tests;

use Bitmap\Bitmap;
use Chinook\Valid\Inline\Album;
use Chinook\Valid\Inline\Artist;
use Chinook\Valid\Inline\Genre;
use Chinook\Valid\Inline\MediaType;
use Chinook\Valid\Inline\Track;
use PDO;

class SaveTest extends EntityTest
{
    public function testAddNewArtist()
    {
        foreach (array_keys(self::$CONNECTIONS) as $connection) {
            $artist = new Artist();
            $artist->name = 'Radiohead';

            $this->assertTrue($artist->save(null, $connection));
            $this->assertNotNull($artist->getId());

            $this->assertEquals(276, $this->getCountArtists($connection));
        }
    }

    /**
     * @param string|int $artist
     * @param array|null $context
     *
     * @dataProvider addNewAlbumData
     */
    public function testAddNewAlbum($artist, $context = null)
    {
        foreach (array_keys(self::$CONNECTIONS) as $connection) {
            $artistIsNew = false;
            if (is_string($artist)) {
                $name = $artist;
                $artist = new Artist();
                $artist->name = $name;
                $artistIsNew = true;
            } else if (is_int($artist)) {
                $artist = Artist::select()->where('ArtistId', '=', $artist)->one(null, $connection);
            }

            $album = new Album();
            $album->setTitle("OK Computer");
            $album->setArtist($artist);

            $this->assertTrue($album->save($context, $connection));
            $this->assertNotNull($album->getId());

            $this->assertEquals(275 + ($artistIsNew ? 1 : 0), $this->getCountArtists($connection));

            $this->assertNotNull($artist->getId());
            $this->assertEquals(348, $this->getCountAlbums($connection));
        }
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

    /**
     * Attempt to add a new song in the database with no declared genre
     */
    public function testAddNewSong()
    {
        foreach (array_keys(self::$CONNECTIONS) as $connection) {
            $track = new Track();
            $track->setName("Silly song");
            $track->setAlbum(Album::select()->where('id', '=', 321)->one(null, $connection));
            $track->setBytes(12345);
            $track->setMilliseconds(123000);
            $track->setComposer("Anonymous");
            $track->setUnitPrice(0.01);
            $track->setMedia(MediaType::select()->where('id', '=', 1)->one(null, $connection));

            $track->save(['genre'], $connection);
            $this->assertNotNull($track->getId());
            $this->assertNotNull($track->getBitmapHash());
        }
    }

    public function testAddNewTrackAndGenre()
    {
        foreach (array_keys(self::$CONNECTIONS) as $connection) {
            $genre = new Genre();
            $genre->setName("Funk");
            $track = new Track();
            $track->setName("B.Y.O.B. (funk version)");
            $track->setUnitPrice(0.99);
            $track->setBytes(4365405);
            $track->setComposer("Tankian, Serj");
            $track->setMilliseconds(217886);
            $track->setGenre($genre);
            $track->setMedia(MediaType::select()->where("MediaTypeId", "=", 1)->one(null, $connection));

            $this->assertTrue($track->save(null, $connection));
            $this->assertNotNull($track->getId());
            $this->assertNotNull($track->getGenre()->getId());

            $this->assertEquals(26, $this->getCountGenres($connection));
        }
    }

    public function testUpdateArtist()
    {
        foreach (array_keys(self::$CONNECTIONS) as $connection) {
            $artist = Artist::select()->where('ArtistId', '=', 179)->one(null, $connection);
            // "Scorpions" in database
            $artist->name = "The Scorpions";
            $this->assertTrue($artist->save(null, $connection));

            $this->assertEquals(15,  $this->getCountArtists($connection, ['name like "The%"']));
            $this->assertEquals(275, $this->getCountArtists($connection));
        }
    }

    public function testUpdateTrackWithNewGenre()
    {
        foreach (array_keys(self::$CONNECTIONS) as $connection) {
            /** @var Genre $genre */
            $genre = new Genre();
            $genre->setName("Funk");
            /** @var Track $track */
            $track = Track::select()->where("TrackId", "=", 2555)->one(null, $connection);
            $track->setGenre($genre);

            $this->assertTrue($track->save(null, $connection));
            $this->assertNotNull($track->getGenre()->getId());

            $this->assertEquals(26, $this->getCountGenres($connection));
        }
    }
}