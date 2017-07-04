<?php

namespace Tests\Bitmap;

use Bitmap\Bitmap;
use Misc\Character;

use Chinook\Valid\Inline\Album as InlineAlbum;
use Chinook\Valid\Inline\Artist as InlineArtist;
use Chinook\Valid\Inline\Employee as InlineEmployee;
use Chinook\Valid\Inline\Track as InlineTrack;
use Chinook\Valid\Arrays\Artist as ArraysArtist;
use Chinook\Valid\Arrays\Album as ArraysAlbum;
use Chinook\Valid\Arrays\Track as ArraysTrack;
use Chinook\Valid\Arrays\Employee as ArraysEmployee;

class SelectTest extends EntityTest
{
    public function getDefaultData()
    {
        return $this->data([[]]);
    }

    public function getArtistDataClasses()
    {
        return $this->dataClasses([InlineArtist::class, ArraysArtist::class]);
    }

    public function getAlbumDataClasses()
    {
        return $this->dataClasses([InlineAlbum::class, ArraysAlbum::class]);
    }

    public function getEmployeeDataClasses()
    {
        return $this->dataClasses([InlineEmployee::class, ArraysEmployee::class]);
    }

    /**
     * @param $artistClass string
     * @param $connection string
     *
     * @dataProvider getArtistDataClasses
     */
    public function testGetNoArtist($artistClass, $connection)
    {
        $artist = $artistClass::select()->where('Name', '=', "Justin Bieber")->one(null, $connection);
        $this->assertNull($artist);
    }

	/**
     * @param $artistClass string
     * @param $connection string
	 * @param $field
	 * @param $id
	 * @param $expected
	 *
	 * @dataProvider getArtistByIdData
	 */
    public function testGetArtistById($artistClass, $connection, $field, $id, $expected)
    {
        $artist = $artistClass::select()->where($field, '=', $id)->one($connection);

        $this->assertNotNull($artist);
        $this->assertSame($expected, $artist->name);
    }

    public function getArtistByIdData()
    {
    	return $this->dataClasses(
            [InlineArtist::class, ArraysArtist::class],
    		[
                ['ArtistId', 94, 'Jimi Hendrix'],
                ['id', 94, 'Jimi Hendrix']
	        ]
        );
    }

    /**
     * @param $artistClass string
     * @param $connection string
     *
     * @dataProvider getArtistDataClasses
     */
    public function testGetArtistAndItsArtWork($artistClass, $connection)
    {
        $artist = $artistClass::select()->where('ArtistId', '=', 22)->one(['albums' => ['tracks', '@artist']], $connection);

        $this->assertNotNull($artist);
        $this->assertEquals(14, sizeof($artist->getAlbums()));

        $this->assertSame($artist, $artist->getAlbums()[0]->getArtist());
    }

    /**
     * @param $albumClass string
     * @param $connection string
     *
     * @dataProvider getAlbumDataClasses
     */
    public function testGetAlbumsByArtist($albumClass, $connection)
    {
        $albums = $albumClass::select()->where('ArtistId', '=', 131)->all(null, $connection);

        $this->assertEquals(2, count($albums));
    }

    /**
     * @param $artistClass string
     * @param $connection string
     *
     * @dataProvider getArtistDataClasses
     */
    public function testGetArtists($artistClass, $connection)
    {
        $artists = $artistClass::select()->where('Name', 'like', 'The%')->all(['albums' => ['@artist']], $connection);

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

            if (sizeof($artist->getAlbums()) > 0) {
	            $this->assertSame($artist, $artist->getAlbums()[0]->getArtist());
            }
        }
    }

    /**
     * @param $albumClass string
     * @param $connection string
     *
     * @dataProvider getAlbumDataClasses
     */
    public function testGetAlbumById($albumClass, $connection)
    {
        $album = $albumClass::select()->where('AlbumId', '=', 148)->one(null, $connection);

        $this->assertNotNull($album);
        $this->assertSame('Black Album', $album->getTitle());
        $this->assertSame('Metallica', $album->getArtist()->name);
        $this->assertEquals(12, sizeof($album->getTracks()));
    }

	/**
     * @param $trackClass string
     * @param $connection string
	 * @param $asc
	 * @param $limit
	 * @param $expected
	 * @param null $offset
	 *
	 * @dataProvider getAlbumTracksOrderedByDurationData
	 */
    public function testGetAlbumTracksOrderedByDuration($trackClass, $connection, $asc, $limit, array $expected, $offset = null)
    {
    	$tracks = $trackClass::select()
		    ->where('album', '=', 164)
		    ->order('Milliseconds', $asc)
		    ->limit($limit, $offset)
		    ->all(null, $connection);

	    $this->assertSameSize($expected, $tracks);

	    for ($i = 0; $i < sizeof($tracks); $i++) {
		    $this->assertEquals($expected[$i], $tracks[$i]->getId());
	    }
    }

    public function getAlbumTracksOrderedByDurationData()
    {
    	return $this->dataClasses(
            [InlineTrack::class, ArraysTrack::class],
    	    [
                [true, 3, [2009, 2011, 2008]],
                [true, 3, [2011, 2008, 2006], 1],
                [false, 3, [2003, 2007, 2004]],
                [false, 3, [2007, 2004, 2014], 1]
            ]
        );
    }

    /**
     * @param $employeeClass string
     * @param $connection string
     *
     * @dataProvider getEmployeeDataClasses
     */
    public function testGetEmployeeAndSuperior($employeeClass, $connection)
    {
        $employee= $employeeClass::select()->where('EmployeeId', '=', 8)->one(['superior' => []], $connection);

        $this->assertNotNull($employee);
        $this->assertNotNull($employee->getSuperior());
        $this->assertEquals($employeeClass, get_class($employee->getSuperior()));
        $this->assertEquals(6, $employee->getSuperior()->getId());
    }

    /**
     * @param $connection string
     *
     * @dataProvider getDefaultData
     */
    public function testGetSiblings($connection)
    {
        Bitmap::current()->connection($connection)->exec("
           create table `Character` (
              id int unsigned auto_increment,
              firstname varchar(16) not null,
              lastname varchar(16) not null,
              father int unsigned,
              mother int unsigned,
              primary key(id)
           )
        ");

        Bitmap::current()->connection($connection)->exec("
            insert into `Character` (id, firstname, lastname, father, mother) values
            (1, 'Homer', 'Simpson', NULL, NULL),
            (2, 'Marge', 'Simpson', NULL, NULL),
            (3, 'Bart', 'Simpson', 1, 2),
            (4, 'Lisa', 'Simpson', 1, 2),
            (5, 'Maggie', 'Simpson', 1, 2)
        ");

        /* @var Character[] $characters */
        $characters = Character::select()
            ->where('id', 'in', '(3,4,5)')
            ->all(null, $connection);

        $this->assertEquals(3, count($characters));

        foreach ($characters as $character) {
            $this->assertNotNull($character->getFather());
            $this->assertEquals(1, $character->getFather()->getId());
            $this->assertNotNull($character->getMother());
            $this->assertEquals(2, $character->getMother()->getId());
        }
    }

}