<?php

namespace Bitmap\Tests;

use Chinook\Valid\Inline\Artist;

class DeleteTest extends EntityTest
{
    public function testDeleteArtist()
    {
        foreach (array_keys(self::$CONNECTIONS) as $connection) {
            $artist = Artist::select()->where('ArtistId', '=', 166)->one(null, $connection);
            // "Avril Lavigne" in database
            $this->assertTrue($artist->delete($connection));

            $this->assertEquals(274, $this->getCountArtists($connection));
        }

    }
}