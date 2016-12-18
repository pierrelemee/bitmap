<?php

namespace Bitmap;

use Chinook\Artist;
use PHPUnit\Framework\TestCase;
use PierreLemee\Bitmap\Bitmap;

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
        //Bitmap::connection(self::CONNECTION_NAME)->rollBack();
    }

    public function testGetArtists()
    {
        /**
         * Artist[]
         */
        $artists = Artist::select('select * from `Artist` where name like "The%"');

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
}