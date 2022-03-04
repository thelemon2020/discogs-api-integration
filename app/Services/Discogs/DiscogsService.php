<?php

namespace App\Services\Discogs;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class DiscogsService
{
    const BASE_URL = 'https://api.discogs.com';
    const RELEASES_ENDPOINT = '/releases';

    public function createRequest(): PendingRequest
    {
        return Http::baseUrl(self::BASE_URL);
    }

    public function release(string $releaseId): array
    {
        return $this->createRequest()->get(self::RELEASES_ENDPOINT . '/' . $releaseId)->json();
    }

    public function releaseRatings(string $releaseId): array
    {
        return $this->createRequest()->get(self::RELEASES_ENDPOINT . '/' . $releaseId . '/ratings')->json();
    }
}
