<?php

namespace Tests\Unit\Services;

use App\Services\Discogs\DiscogsService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DiscogsServiceTest extends TestCase
{
    /** @test */
    public function itCanBeCreated(): void
    {
        $service = app()->make(DiscogsService::class);
        $this->assertInstanceOf(DiscogsService::class, $service);
    }

    /** @test */
    public function itCanCreateARequest(): void
    {
        /** @var DiscogsService $service */
        $service = app()->make(DiscogsService::class);
        $request = $service->createRequest();
        $this->assertInstanceOf(PendingRequest::class, $request);
    }

    /** @test */
    public function itCanGetARelease(): void
    {
        Http::fake([
            DiscogsService::BASE_URL . DiscogsService::RELEASES_ENDPOINT . '/test' => Http::response(
                ['foo' => 'bar'],
                200
            )
        ]);
        $service = app()->make(DiscogsService::class);
        $response = $service->release('test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }

    /** @test */
    public function itCanGetRatingsForARelease(): void
    {
        $endpoint = DiscogsService::BASE_URL . DiscogsService::RELEASES_ENDPOINT . '/test/ratings';
        Http::fake([$endpoint => Http::response(['foo' => 'bar'], 200)]);
        $service = app()->make(DiscogsService::class);
        $response = $service->releaseRatings('test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }

    /** @test */
    public function itCanGetRatingsForAReleaseByUser(): void
    {
        $endpoint = DiscogsService::BASE_URL . DiscogsService::RELEASES_ENDPOINT . '/test/ratings/user';
        Http::fake([$endpoint => Http::response(['foo' => 'bar'], 200)]);
        $service = app()->make(DiscogsService::class);
        $response = $service->releaseRatings('test', 'user');
        $this->assertEquals(['foo' => 'bar'], $response);
    }

    /** @test */
    public function itCanGetTheStatsForARelease(): void
    {
        $endpoint = DiscogsService::BASE_URL . DiscogsService::RELEASES_ENDPOINT . '/test/stats';
        Http::fake([$endpoint => Http::response(['foo' => 'bar'], 200)]);
        $service = app()->make(DiscogsService::class);
        $response = $service->releaseStats('test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }

    /** @test */
    public function itCanGetAMasterRelease(): void
    {
        $endpoint = DiscogsService::BASE_URL . DiscogsService::MASTERS_ENDPOINT . '/test';
        Http::fake([$endpoint => Http::response(['foo' => 'bar'], 200)]);
        $service = app()->make(DiscogsService::class);
        $response = $service->masterRelease('test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }

    /** @test */
    public function itCanGetMasterReleaseVersions(): void
    {
        $endpoint = DiscogsService::BASE_URL . DiscogsService::MASTERS_ENDPOINT . '/test/versions';
        Http::fake([$endpoint => Http::response(['foo' => 'bar'], 200)]);
        $service = app()->make(DiscogsService::class);
        $response = $service->masterReleaseVersions('test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }

    /** @test */
    public function itCanGetMasterReleaseVersionsWithPagination(): void
    {
        $endpoint = DiscogsService::BASE_URL . DiscogsService::MASTERS_ENDPOINT . '/test/versions?page=2&per_page=8';
        Http::fake([$endpoint => Http::response(['foo' => 'bar'], 200)]);
        $service = app()->make(DiscogsService::class);
        $response = $service->page(2)->perPage(8)->masterReleaseVersions('test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }

    /** @test */
    public function itResetsTheQueryArrayAfterARequest(): void
    {
        $firstEndpoint = DiscogsService::BASE_URL . DiscogsService::MASTERS_ENDPOINT . '/test/versions?page=2&per_page=10';
        $secondEndpoint = DiscogsService::BASE_URL . DiscogsService::MASTERS_ENDPOINT . '/test/versions';
        Http::fake([
            $firstEndpoint => Http::response(['foo' => 'bar'], 200),
            $secondEndpoint => Http::response(['bar' => 'foo'], 200),
        ]);
        $service = app()->make(DiscogsService::class);
        $response = $service->page(2)->perPage(10)->masterReleaseVersions('test');
        $responseTwo = $service->masterReleaseVersions('test');
        $this->assertEquals(['foo' => 'bar'], $response);
        $this->assertEquals(['bar' => 'foo'], $responseTwo);
    }

    /** @test */
    public function itCanUseACustomQueryString(): void
    {
        $endpoint = DiscogsService::BASE_URL . DiscogsService::MASTERS_ENDPOINT . '/test/versions?format=vinyl';
        Http::fake([
            $endpoint => Http::response(['foo' => 'bar'], 200),
        ]);

        $service = app()->make(DiscogsService::class);
        $response = $service->query('format', 'vinyl')->masterReleaseVersions('test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }

    /** @test */
    public function itCanUseMoreThanOneCustomValueInQueryString(): void
    {
        $endpoint = DiscogsService::BASE_URL . DiscogsService::MASTERS_ENDPOINT
            . '/test/versions?key=value&growth=session';

        Http::fake([
            $endpoint => Http::response(['foo' => 'bar'], 200),
        ]);

        $service = app()->make(DiscogsService::class);
        $response = $service->queries(['key' => 'value', 'growth' => 'session'])->masterReleaseVersions('test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }

    /** @test */
    public function itCanGetAnArtiste(): void
    {
        $endpoint = DiscogsService::BASE_URL . DiscogsService::ARTIST_ENDPOINT . '/test';
        Http::fake([$endpoint => Http::response(['foo' => 'bar'], 200)]);
        $service = app()->make(DiscogsService::class);
        $response = $service->artist('test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }

    /** @test */
    public function itCanGetArtistReleases(): void
    {
        $endpoint = DiscogsService::BASE_URL . DiscogsService::ARTIST_ENDPOINT . '/test' . '/releases';
        Http::fake([$endpoint => Http::response(['foo' => 'bar'], 200)]);
        $service = app()->make(DiscogsService::class);
        $response = $service->artistReleases('test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }
    /** @test */
    public function itCanGetArtistReleasesWithSorting(): void
    {
        $endpoint = DiscogsService::BASE_URL . DiscogsService::ARTIST_ENDPOINT . '/test' . '/releases?sort=year';
        Http::fake([$endpoint => Http::response(['foo' => 'bar'], 200)]);
        $service = app()->make(DiscogsService::class);
        $response = $service->query('sort', 'year')->artistReleases('test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }

    /** @test */
    public function itCanGetASpecificLabel(): void
    {
        $endpoint = DiscogsService::BASE_URL . DiscogsService::LABEL_ENDPOINT . '/test';
        Http::fake([$endpoint => Http::response(['foo' => 'bar'], 200)]);
        $service = app()->make(DiscogsService::class);
        $response = $service->label('test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }

    /** @test */
    public function itCanGetAllLabelReleases(): void
    {
        $endpoint = DiscogsService::BASE_URL . DiscogsService::LABEL_ENDPOINT . '/test/releases';
        Http::fake([$endpoint => Http::response(['foo' => 'bar'], 200)]);
        $service = app()->make(DiscogsService::class);
        $response = $service->labelReleases('test');
        $this->assertEquals(['foo' => 'bar'], $response);
    }
}
