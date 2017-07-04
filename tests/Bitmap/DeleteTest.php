<?php

namespace Tests\Bitmap;

use Chinook\Valid\Inline\Artist;

class DeleteTest extends EntityTest
{
    /**
     * @param $connection
     *
     * @dataProvider deleteArtistData
     */
    public function testDeleteArtist($connection)
    {
        $artist = Artist::select()->where('ArtistId', '=', 166)->one(null, $connection);
        // "Avril Lavigne" in database
        $this->assertTrue($artist->delete($connection));

        $this->assertEquals(274, $this->queryCount($connection, 'Artist'));
    }

    public function deleteArtistData()
    {
        return array_map(function ($connection) {return [$connection];}, $this->getConnectionNames());
    }
}