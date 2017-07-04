<?php

namespace Tests\Bitmap;

use Chinook\Valid\Inline\Album as InlineAlbum;
use Chinook\Valid\Inline\Artist as InlineArtist;
use Chinook\Valid\Inline\Genre as InlineGenre;
use Chinook\Valid\Inline\MediaType as InlineMediaType;
use Chinook\Valid\Inline\Track as InlineTrack;
use Chinook\Valid\Arrays\Artist as ArraysArtist;
use Chinook\Valid\Arrays\Album as ArraysAlbum;
use Chinook\Valid\Arrays\Genre as ArraysGenre;
use Chinook\Valid\Arrays\MediaType as ArraysMediaType;
use Chinook\Valid\Arrays\Track as ArraysTrack;

class SaveTest extends EntityTest
{
    public function getDefaultData()
    {
        return $this->data([[]]);
    }

    public function getArtistDataClasses()
    {
        return $this->dataClasses([InlineArtist::class, ArraysArtist::class]);
    }

    /**
     * @param $artistClass string
     * @param $connection string
     *
     * @dataProvider getArtistDataClasses
     */
    public function testAddNewArtist($artistClass, $connection)
    {
        $artist = new $artistClass();
        $artist->name = 'Radiohead';

        $this->assertTrue($artist->save(null, $connection));
        $this->assertNotNull($artist->getId());

        $this->assertEquals(276, $this->queryCount($connection, 'Artist'));
    }

    /**
     * @param $artistClass string
     * @param $albumClass string
     * @param $connection string
     * @param string|int $artist
     * @param array|null $context
     *
     * @dataProvider addNewAlbumData
     */
    public function testAddNewAlbum($artistClass, $albumClass, $connection, $artist, $context = null)
    {
        $artistIsNew = false;
        if (is_string($artist)) {
            $name = $artist;
            $artist = new $artistClass();
            $artist->name = $name;
            $artistIsNew = true;
        } else if (is_int($artist)) {
            $artist = $artistClass::select()->where('ArtistId', '=', $artist)->one(null, $connection);
        }

        $album = new $albumClass();
        $album->setTitle("OK Computer");
        $album->setArtist($artist);

        $this->assertTrue($album->save($context, $connection));
        $this->assertNotNull($album->getId());

        $this->assertEquals(275 + ($artistIsNew ? 1 : 0), $this->queryCount($connection, 'Artist'));

        $this->assertNotNull($artist->getId());
        $this->assertEquals(348, $this->queryCount($connection, 'Album'));
    }

    public function addNewAlbumData()
    {
        return $this->dataClasses(
            [
                [InlineArtist::class, InlineAlbum::class],
                [ArraysArtist::class, ArraysAlbum::class]
            ],
            [
                [ 'Radiohead', ['artist']],
                [ 'Radiohead', ['artist' => 1]],
                [ 'Radiohead'],
                [ 193, ['artist']],
                [ 193, ['artist' => 1]],
                [ 193 ]
            ]
        );
    }

    /**
     * Attempt to add a new song in the database with no declared genre
     *
     * @param $trackClass string
     * @param $albumClass string
     * @param $mediaClass string
     * @param $connection string
     *
     * @dataProvider addNewSongData
     */
    public function testAddNewSong($trackClass, $albumClass, $mediaClass, $connection)
    {
        $track = new $trackClass();
        $track->setName("Silly song");
        $track->setAlbum($albumClass::select()->where('id', '=', 321)->one(null, $connection));
        $track->setBytes(12345);
        $track->setMilliseconds(123000);
        $track->setComposer("Anonymous");
        $track->setUnitPrice(0.01);
        $track->setMedia($mediaClass::select()->where('id', '=', 1)->one(null, $connection));

        $track->save(['genre'], $connection);
        $this->assertNotNull($track->getId());
        $this->assertNotNull($track->getBitmapHash());
        $this->assertEquals(3504, $this->queryCount($connection, 'Track'));
    }

    public function addNewSongData()
    {
        return $this->dataClasses([
            [InlineTrack::class, InlineAlbum::class, InlineMediaType::class],
            [ArraysTrack::class, ArraysAlbum::class, ArraysMediaType::class]
        ]);
    }

    /**
     * @param $genreClass string
     * @param $trackClass string
     * @param $mediaClass string
     * @param $connection string
     *
     * @dataProvider addNewTrackAndGenreData
     */
    public function testAddNewTrackAndGenre($genreClass, $trackClass, $mediaClass, $connection)
    {
        $genre = new $genreClass();
        $genre->setName("Funk");
        $track = new $trackClass();
        $track->setName("B.Y.O.B. (funk version)");
        $track->setUnitPrice(0.99);
        $track->setBytes(4365405);
        $track->setComposer("Tankian, Serj");
        $track->setMilliseconds(217886);
        $track->setGenre($genre);
        $track->setMedia($mediaClass::select()->where("MediaTypeId", "=", 1)->one(null, $connection));

        $this->assertTrue($track->save(null, $connection));
        $this->assertNotNull($track->getId());
        $this->assertNotNull($track->getGenre()->getId());

        $this->assertEquals(26, $this->queryCount($connection, 'Genre'));
    }

    public function addNewTrackAndGenreData()
    {
        return $this->dataClasses([
            [InlineGenre::class, InlineTrack::class, InlineMediaType::class],
            [ArraysGenre::class, ArraysTrack::class, ArraysMediaType::class]
        ]);
    }

    /**
     * @param $artistClass string
     * @param $connection string
     *
     * @dataProvider getArtistDataClasses
     */
    public function testUpdateArtist($artistClass, $connection)
    {
        $artist = $artistClass::select()->where('ArtistId', '=', 179)->one(null, $connection);
        // "Scorpions" in database
        $artist->name = "The Scorpions";
        $this->assertTrue($artist->save(null, $connection));

        $this->assertEquals(15, $this->queryValue($connection, 'select count(*) as `total` from `Artist` where name like "The%"'));
        $this->assertEquals(275, $this->queryCount($connection, 'Artist'));
    }

    /**
     * @param $genreClass string
     * @param $trackClass string
     * @param $connection string
     *
     * @dataProvider updateTrackWithNewGenreData
     */
    public function testUpdateTrackWithNewGenre($genreClass, $trackClass, $connection)
    {
        $genre = new $genreClass();
        $genre->setName("Funk");
        $track = $trackClass::select()->where("TrackId", "=", 2555)->one(null, $connection);
        $track->setGenre($genre);

        $this->assertTrue($track->save(null, $connection));
        $this->assertNotNull($track->getGenre()->getId());

        $this->assertEquals(26, $this->queryCount($connection, 'Genre'));
    }

    public function updateTrackWithNewGenreData()
    {
        return $this->dataClasses([
            [InlineGenre::class, InlineTrack::class],
            [ArraysGenre::class, ArraysTrack::class]
        ]);
    }
}