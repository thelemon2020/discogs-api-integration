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
    public function itCanGetMultipleReleases(): void
    {
        Http::fake([
            DiscogsService::BASE_URL . DiscogsService::RELEASES_ENDPOINT => Http::response(['foo' => 'bar'], 200)
        ]);
        $service = app()->make(DiscogsService::class);
        $response = $service->releases();
        $this->assertEquals(['foo' => 'bar'], $response);
    }
}
