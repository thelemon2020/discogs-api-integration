<?php

namespace Tests\Services\Discogs\Models;

use App\Services\Discogs\Models\Artist;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ArtistTest extends TestCase
{
    /** @test */
    public function itCanCreateAnArtist(): void
    {
        $artist = new Artist();
        $this->assertInstanceOf(Artist::class, $artist);
    }

    /** @test */
    public function itHasExpectedProperties(): void
    {
        //TODO finish writing class
        $artistData = $this->getArtistData();

        $artist = new Artist($artistData->toArray());

        $artistData->each(function (mixed $data, string $key) use ($artist) {
            $this->assertEquals($data, $artist->{$key});
        });
    }

    private function getArtistData(): Collection
    {
        return collect(json_decode(file_get_contents(__DIR__ . '/../fixtures/artist.json'), true));
    }
}
