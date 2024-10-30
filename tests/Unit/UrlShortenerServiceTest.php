<?php

/**
 * This file contains the unit tests for the UrlShortenerService class.
 * It ensures that all business logic related to URL shortening is functioning correctly.
 *
 * @category Testing
 * @package  Tests\Unit
 * @author   Szaniszló Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     http://example.com
 */

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\UrlShortenerService;
use App\Repositories\UrlRepository;
use PHPUnit\Framework\TestCase;
use Mockery;

/**
 * Unit tests for the UrlShortenerService class.
 *
 * @category Testing
 * @package  Tests\Unit
 * @author   Szaniszló Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     http://example.com
 */
class UrlShortenerServiceTest extends TestCase
{
    /**
     * The instance of UrlShortenerService to be tested.
     *
     * @var UrlShortenerService
     */
    protected UrlShortenerService $urlShortenerService;

    /**
     * Mock instance of UrlRepository for dependency injection.
     *
     * @var UrlRepository|\Mockery\MockInterface
     */
    protected $urlRepositoryMock;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        /**
         * Create a mock for UrlRepository.
         *
         * @var UrlRepository $mock
         */
        $mock = Mockery::mock(UrlRepository::class);
        $this->urlRepositoryMock = $mock;
        $this->urlShortenerService = new UrlShortenerService($this->urlRepositoryMock);
    }

    /**
     * Tear down the test environment.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that shortenUrl generates a unique short code and saves the URL to the database.
     *
     * @return void
     */
    public function testShortenUrlGeneratesAndSavesShortCode(): void
    {
        $originalUrl = 'https://example.com';
        
        $this->urlRepositoryMock
            ->shouldReceive('existsByCode')
            ->with(Mockery::any())
            ->andReturn(false);
    
        $this->urlRepositoryMock
            ->shouldReceive('create')
            ->with(Mockery::any(), Mockery::any())
            ->andReturn(Mockery::mock(\App\Models\Url::class));
    
        $shortUrl = $this->urlShortenerService->shortenUrl($originalUrl);
    
        $this->assertStringStartsWith("/jump/", $shortUrl);
    }    

    /**
     * Helper function to invoke a private method of an object.
     *
     * @param object $object     The object to invoke the method on.
     * @param string $methodName The name of the method to invoke.
     * @param array  $parameters The parameters to pass to the method.
     *
     * @return mixed The result of the invoked method.
     */
    protected function invokePrivateMethod(object $object, string $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Test that shortenUrl handles code collisions by regenerating a unique code.
     *
     * @return void
     */
    public function testShortenUrlHandlesCollision(): void
    {
        $originalUrl = 'https://collision-test.com';

        // First call to existsByCode returns true (collision), second call returns false (no collision)
        $this->urlRepositoryMock
            ->shouldReceive('existsByCode')
            ->andReturn(true, false);

        $this->urlRepositoryMock
            ->shouldReceive('create')
            ->with(Mockery::any(), Mockery::any())
            ->andReturn(Mockery::mock(\App\Models\Url::class));

        $shortUrl = $this->urlShortenerService->shortenUrl($originalUrl);

        $this->assertStringStartsWith("/jump/", $shortUrl);
    }

    /**
     * Test that shortenUrl throws an exception for an invalid URL.
     *
     * @return void
     */
    public function testShortenUrlThrowsExceptionForInvalidUrl(): void
    {
        $this->urlRepositoryMock
            ->shouldReceive('existsByCode')
            ->andReturn(false);
            
        $this->urlRepositoryMock
            ->shouldReceive('create')
            ->andReturn(Mockery::mock(\App\Models\Url::class));

        $this->expectException(\InvalidArgumentException::class);

        $invalidUrl = 'invalid-url';
        $this->urlShortenerService->shortenUrl($invalidUrl);
    }

    

    /**
     * Test that generateShortCode produces a 6-character unique code.
     *
     * @return void
     */
    public function testGenerateShortCodeProducesUniqueCode(): void
    {
        $this->urlRepositoryMock
            ->shouldReceive('existsByCode')
            ->andReturn(false);

        $originalUrl = 'https://example.com';
        $shortCode = $this->invokePrivateMethod($this->urlShortenerService, '_generateShortCode', [$originalUrl]);

        $this->assertEquals(6, strlen($shortCode));
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]{6}$/', $shortCode);
    }
}
