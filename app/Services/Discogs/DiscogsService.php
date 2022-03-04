<?php

namespace App\Services\Discogs;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class DiscogsService
{
    const BASE_URL = 'https://api.discogs.com';
    const RELEASES_ENDPOINT = '/releases';
    const MASTERS_ENDPOINT = '/masters';

    public function createRequest(): PendingRequest
    {
        return Http::baseUrl(self::BASE_URL);
    }

    public function release(string $releaseId): array
    {
        return $this->createRequest()->get(self::RELEASES_ENDPOINT . '/' . $releaseId)->json();
    }

    public function releaseStats(string $releaseId): array
    {
        return $this->createRequest()->get(self::RELEASES_ENDPOINT . '/' . $releaseId . '/stats')->json();
    }

    public function releaseRatings(string $releaseId, ?string $user = null): array
    {
        $url = self::RELEASES_ENDPOINT . '/' . $releaseId . '/ratings';
        $url = $user ? $url . '/' . $user : $url;
        return $this->createRequest()->get($url)->json();
    }

    public function masterRelease(string $releaseId)
    {
        $url = self::MASTERS_ENDPOINT . '/' . $releaseId;
        return $this->createRequest()->get($url)->json();
    }

    public function masterReleaseVersions(string $releaseId)
    {
        $url = self::MASTERS_ENDPOINT . '/' . $releaseId . '/versions';
        return $this->createRequest()->get($url)->json();
    }
}
