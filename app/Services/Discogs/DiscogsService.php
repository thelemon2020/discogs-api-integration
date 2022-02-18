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

    public function releases(?string $releaseId = null): array
    {
        return $this->createRequest()->get(
            self::RELEASES_ENDPOINT . ($releaseId !== null ? ('/' . $releaseId) : '')
        )->json();
    }
}
