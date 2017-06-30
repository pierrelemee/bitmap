<?php

namespace Bitmap;

use Chinook\Valid\Inline\Album;
use Chinook\Valid\Inline\Artist;
use Chinook\Valid\Inline\Employee;
use Chinook\Valid\Inline\Track;
use Misc\Character;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
    const CONNECTION_SQLITE = 'chinook_sqlite';
    const CONNECTION_MYSQL  = 'chinook_mysql';


    public static function setUpBeforeClass()
    {

    	if (isset(Logger::getLevels()[strtoupper(getenv('PHPUNIT_LOGGING'))])) {
		    Bitmap::current()->setLogger(new Logger(new StreamHandler(fopen('php://stdout', 'a'), strtoupper(getenv('PHPUNIT_LOGGING')))));
	    }

        foreach (self::connections() as $name => $arguments) {
            Bitmap::current()->addConnection($name, $arguments[0], false, isset($arguments[1]) ? $arguments[1] : null, isset($arguments[2]) ? $arguments[2] : null);
        }
    }

    /**
     * @before
     */
    public function before()
    {
        foreach (self::connections() as $name => $arguments) {
            Bitmap::current()->connection($name)->beginTransaction();
        }
    }

    /**
     * @after
     */
    public function after()
    {
        foreach (self::connections() as $name => $arguments) {
            Bitmap::current()->connection($name)->rollBack();
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

	/**
	 * @param $field
	 * @param $id
	 * @param $expected
	 *
	 * @dataProvider getArtistByIdData
	 */
    public function testGetArtistById($field, $id, $expected)
    {
        foreach (array_keys(self::connections()) as $connection) {
            /** @var Artist $artist */
            $artist = Artist::select()->where($field, '=', $id)->one($connection);

            $this->assertNotNull($artist);
            $this->assertSame($expected, $artist->name);
        }
    }

    public function getArtistByIdData()
    {
    	return [
    		['ArtistId', 94, 'Jimi Hendrix'],
    		['id', 94, 'Jimi Hendrix']
	    ];
    }

    public function testGetArtistAndItsArtWork()
    {
        /** @var Artist $artist */
        $artist = Artist::select()->where('ArtistId', '=', 22)->one(['albums' => ['tracks', '@artist']]);

        $this->assertNotNull($artist);
        $this->assertEquals(14, sizeof($artist->getAlbums()));

        $this->assertSame($artist, $artist->getAlbums()[0]->getArtist());
    }

    public function testGetAlbumsByArtist()
    {
        $albums = Album::select()->where('ArtistId', '=', 131)->all();

        $this->assertEquals(2, sizeof($albums));
    }

    public function testGetArtists()
    {
        /** @var Artist[] */
        $artists = Artist::select()->where('Name', 'like', 'The%')->all(['albums' => ['@artist']]);

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

        /** @var Artist $artist*/
        foreach ($artists as $artist) {
            $this->assertArrayHasKey($artist->getId(), $expected);
            $this->assertEquals($expected[$artist->getId()], $artist->name);

            if (sizeof($artist->getAlbums()) > 0) {
	            $this->assertSame($artist, $artist->getAlbums()[0]->getArtist());
            }
        }
    }

    public function testGetAlbumById()
    {
        /** @var Album $album */
        $album = Album::select()->where('AlbumId', '=', 148)->one();

        $this->assertNotNull($album);
        $this->assertSame('Black Album', $album->getTitle());
        $this->assertSame('Metallica', $album->getArtist()->name);
        $this->assertEquals(12, sizeof($album->getTracks()));
    }

	/**
	 * @param $asc
	 * @param $limit
	 * @param $expected
	 * @param null $offset
	 *
	 * @dataProvider getAlbumTracksOrderedByDurationData
	 */
    public function testGetAlbumTracksOrderedByDuration($asc, $limit, array $expected, $offset = null)
    {
    	/** @var Track[] $tracks */
	    $tracks = Track::select()
		    ->where('album', '=', 164)
		    ->order('Milliseconds', $asc)
		    ->limit($limit, $offset)
		    ->all();

	    $this->assertSameSize($expected, $tracks);

	    for ($i = 0.; $i < sizeof($tracks); $i++) {
		    $this->assertEquals($expected[$i], $tracks[$i]->getId());
	    }
    }

    public function getAlbumTracksOrderedByDurationData()
    {
    	return [
    		[true, 3, [2009, 2011, 2008]],
		    [true, 3, [2011, 2008, 2006], 1],
		    [false, 3, [2003, 2007, 2004]],
		    [false, 3, [2007, 2004, 2014], 1]
	    ];
    }

    public function testGetEmployeeAndSuperior()
    {
        /** @var Employee $employee */
        $employee= Employee::select()->where('EmployeeId', '=', 8)->one(['superior' => []]);

        $this->assertNotNull($employee);
        $this->assertNotNull($employee->getSuperior());
        $this->assertEquals(Employee::class, get_class($employee->getSuperior()));
        $this->assertEquals(6, $employee->getSuperior()->getId());
    }

    public function testGetSiblings()
    {
        $connection = Bitmap::current()->connection(self::CONNECTION_SQLITE);
        $connection->exec("
           create table Character(
              id int unsigned auto_increment,
              firstname varchar(16) not null,
              lastname varchar(16) not null,
              father int unsigned,
              mother int unsigned,
              primary key(id)
           )
        ");

        $connection->exec("
            insert into Character (id, firstname, lastname, father, mother) values
            (1, 'Homer', 'Simpson', NULL, NULL),
            (2, 'Marge', 'Simpson', NULL, NULL),
            (3, 'Bart', 'Simpson', 1, 2),
            (4, 'Lisa', 'Simpson', 1, 2),
            (5, 'Maggie', 'Simpson', 1, 2)
        ");

        /* @var Character[] $characters */
        $characters = Character::select()
            ->where('id', 'in', '(3,4,5)')
            ->all(null,self::CONNECTION_SQLITE);

        $this->assertEquals(3, count($characters));

        foreach ($characters as $character) {
            $this->assertNotNull($character->getFather());
            $this->assertEquals(1, $character->getFather()->getId());
            $this->assertNotNull($character->getMother());
            $this->assertEquals(2, $character->getMother()->getId());
        }
    }

}