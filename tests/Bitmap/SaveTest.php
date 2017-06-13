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

    public function testAddNewAlbumWithTracks()
    {
        foreach (array_keys(self::$CONNECTIONS) as $connection) {
            /** @var Genre $genre */
            $genre = Genre::select()->where('id', '=', 4)->one(null, $connection);
            $artist = Artist::select()->where('id', '=', 127)->one(null, $connection);
            $composer = "Anthony Kiedis/Chad Smith/Flea/John Frusciante";
            $price = 0.99;
            /** @var MediaType $media */
            $media = MediaType::select()->where('id', '=', 1)->one(null, $connection);
            $album = new Album();
            $album->setTitle("One Hot Minute");
            $album->setArtist($artist);
            $tracks = [
                self::createTrack("Warped", $genre, $media, 304, $composer, $price),
                self::createTrack("Aeroplane", $genre, $media, 285, $composer, $price),
                self::createTrack("Deep kick", $genre, $media, 393, $composer, $price),
                self::createTrack("My Friends", $genre, $media, 242, $composer, $price),
                self::createTrack("Coffee Shop", $genre, $media, 188, $composer, $price),
                self::createTrack("Pea", $genre, $media, 107, $composer, $price),
                self::createTrack("One Big Mob", $genre, $media, 362, $composer, $price),
                self::createTrack("Walkabout", $genre, $media, 307, $composer, $price),
                self::createTrack("Tearjerker", $genre, $media, 259, $composer, $price),
                self::createTrack("One hot minute", $genre, $media, 383, $composer, $price),
                self::createTrack("Failing into Grace", $genre, $media, 228, $composer, $price),
                self::createTrack("Shallow By The Game", $genre, $media, 273, $composer, $price),
                self::createTrack("Transcending", $genre, $media, 346, $composer, $price)
            ];
            $album->setTracks($tracks);

            $album->save(null, $connection);
            $this->assertEquals(3503 + count($tracks), $this->getCountTracks($connection));
        }
    }

    /**
     * @param string $title string
     * @param Genre $genre
     * @param MediaType $media
     * @param int $duration
     * @param string $composer
     * @param float $price
     *
     * @return Track
     */
    private static function createTrack($title, Genre $genre, MediaType $media, $duration, $composer, $price)
    {
        $track = new Track();
        $track->setName($title);
        $track->setGenre($genre);
        $track->setMedia($media);
        $track->setMilliseconds($duration);
        $track->setBytes($duration * 32);
        $track->setComposer($composer);
        $track->setUnitPrice($price);
        return $track;
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