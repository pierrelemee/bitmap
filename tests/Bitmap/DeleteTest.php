<?php

namespace Tests\Bitmap;

use Chinook\Valid\Inline\Artist as InlineArtist;
use Chinook\Valid\Arrays\Artist as ArraysArtist;

class DeleteTest extends EntityTest
{
    /**
     * @param $connection
     * @param $classname
     *
     * @dataProvider deleteArtistData
     */
    public function testDeleteArtist($connection, $classname)
    {
        $artist = $classname::select()->where('ArtistId', '=', 166)->one(null, $connection);
        // "Avril Lavigne" in database
        $this->assertTrue($artist->delete($connection));

        $this->assertEquals(274, $this->queryCount($connection, 'Artist'));
    }

    public function deleteArtistData()
    {
        return $this->data([
            [InlineArtist::class],
            [ArraysArtist::class]
        ]);
    }
}