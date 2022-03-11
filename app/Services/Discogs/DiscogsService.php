<?php

namespace App\Services\Discogs;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class DiscogsService
{
    const BASE_URL = 'https://api.discogs.com';
    const RELEASES_ENDPOINT = '/releases';
    const MASTERS_ENDPOINT = '/masters';
    const ARTIST_ENDPOINT = '/artists';
    const LABEL_ENDPOINT = '/labels';
    const SEARCH_ENDPOINT = '/database/search';

    private array $queryParams = [];

    public function createRequest(): PendingRequest
    {
        return Http::baseUrl(self::BASE_URL);
    }

    private function buildUrl(string $baseUrl): string
    {
        if($this->queryParams) {
            $baseUrl .= '?' . http_build_query($this->queryParams);
        }
        $this->queryParams = [];
        return $baseUrl;
    }

    public function page(int $page = 1): DiscogsService
    {
        return $this->query('page', $page);
    }

    public function perPage(int $perPage = 25): DiscogsService
    {
        return $this->query('per_page', $perPage);
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

    public function query(string $key, mixed $value): DiscogsService
    {
        $this->queryParams[$key] = (string)$value;
        return $this;
    }

    public function queries(array $queries): DiscogsService
    {
        foreach($queries as $key => $value) {
            $this->query($key, $value);
        }

        return $this;
    }

    public function masterRelease(string $releaseId)
    {
        $url = self::MASTERS_ENDPOINT . '/' . $releaseId;
        return $this->createRequest()->get($url)->json();
    }

    public function masterReleaseVersions(string $releaseId)
    {
        $url = $this->buildUrl(self::MASTERS_ENDPOINT . '/' . $releaseId . '/versions');

        return $this->createRequest()->get($url)->json();
    }

    public function artist(string $artistId)
    {
        $url = self::ARTIST_ENDPOINT . '/' . $artistId;
        return $this->createRequest()->get($url)->json();
    }

    public function artistReleases(string $artistId)
    {
        $url = $this->buildUrl(self::ARTIST_ENDPOINT . '/' . $artistId . '/releases');
        return $this->createRequest()->get($url)->json();
    }

    public function label(string $labelId)
    {
        $url = self::LABEL_ENDPOINT . '/' . $labelId;
        return $this->createRequest()->get($url)->json();
    }

    public function labelReleases(string $labelId) {
        $url = $this->buildUrl(self::LABEL_ENDPOINT . '/' . $labelId . '/releases');
        return $this->createRequest()->get($url)->json();
    }

    public function search(string $query)
    {
        $this->query('q', $query);
        return $this->createRequest()->get($this->buildUrl(self::SEARCH_ENDPOINT))->json();
    }
}
