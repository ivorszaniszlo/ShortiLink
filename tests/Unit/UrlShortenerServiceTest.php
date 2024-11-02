<?php
declare(strict_types=1);

/**
 * Unit tests for the UrlShortenerService class.
 *
 * @category Test
 * @package  Tests\Unit
 */

namespace Tests\Unit;

use App\Services\UrlShortenerService;
use App\Repositories\UrlRepository;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\QueryException;
use InvalidArgumentException;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class UrlShortenerServiceTest
 *
 * Tests the functionality of the UrlShortenerService class.
 */
class UrlShortenerServiceTest extends TestCase
{
    /**
     * The service under test.
     *
     * @var UrlShortenerService
     */
    protected UrlShortenerService $urlShortenerService;

    /**
     * Mock instance of UrlRepository.
     *
     * @var MockInterface
     */
    protected $urlRepositoryMock;

    /**
     * Mock instance of UrlGenerator.
     *
     * @var MockInterface
     */
    protected $urlGeneratorMock;

    /**
     * Sets up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->urlRepositoryMock = Mockery::mock(UrlRepository::class);
        $this->urlGeneratorMock = Mockery::mock(UrlGenerator::class);

        $this->urlShortenerService = new UrlShortenerService(
            $this->urlRepositoryMock,
            $this->urlGeneratorMock
        );
    }

    /**
     * Tears down the test environment.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Tests that shortenUrl generates and saves a short code.
     */
    public function testShortenUrlGeneratesAndSavesShortCode(): void
    {
        $originalUrl = 'https://example.com';
        $normalizedUrl = 'example.com';
        $shortCode = 'abc123';
        $shortUrl = "http://localhost/jump/{$shortCode}";

        $this->urlRepositoryMock
            ->shouldReceive('findByNormalizedUrl')
            ->with($normalizedUrl)
            ->andReturn(null);

        $this->urlRepositoryMock
            ->shouldReceive('create')
            ->with($originalUrl, $normalizedUrl, $shortCode)
            ->andReturn(Mockery::mock(\App\Models\Url::class));

        $this->urlGeneratorMock
            ->shouldReceive('to')
            ->with("/jump/{$shortCode}")
            ->andReturn($shortUrl);

        // Mock the generateShortCode method
        $urlShortenerServiceMock = Mockery::mock(
            UrlShortenerService::class,
            [$this->urlRepositoryMock, $this->urlGeneratorMock]
        )->makePartial()->shouldAllowMockingProtectedMethods();

        $urlShortenerServiceMock
            ->shouldReceive('generateShortCode')
            ->andReturn($shortCode);

        $result = $urlShortenerServiceMock->shortenUrl($originalUrl);

        $this->assertEquals($shortUrl, $result);
    }

    /**
     * Tests that shortenUrl handles collisions during short code generation.
     */
    public function testShortenUrlHandlesCollision(): void
    {
        $originalUrl = 'https://collision-test.com';
        $normalizedUrl = 'collision-test.com';
        $firstShortCode = 'def456';
        $secondShortCode = 'ghi789';
        $shortUrl = "http://localhost/jump/{$secondShortCode}";

        $this->urlRepositoryMock
            ->shouldReceive('findByNormalizedUrl')
            ->with($normalizedUrl)
            ->andReturn(null);

        // Mock a UrlShortenerService példány létrehozása a generateShortCode metódus vezérléséhez
        $urlShortenerServiceMock = Mockery::mock(
            UrlShortenerService::class,
            [$this->urlRepositoryMock, $this->urlGeneratorMock]
        )->makePartial()->shouldAllowMockingProtectedMethods();

        // Az első hívásnál $firstShortCode, a másodiknál $secondShortCode
        $urlShortenerServiceMock
            ->shouldReceive('generateShortCode')
            ->andReturn($firstShortCode, $secondShortCode);

        // Egyedi megszorítás megsértésének szimulálása az első próbálkozásnál
        $pdoException = new \PDOException('Unique constraint violation');
        $pdoException->errorInfo = ['23000', 1062, 'Duplicate entry'];

        $queryException = new QueryException(
            '',
            '',
            [],
            $pdoException
        );

        $this->urlRepositoryMock
            ->shouldReceive('create')
            ->with($originalUrl, $normalizedUrl, $firstShortCode)
            ->andThrow($queryException);

        // A második próbálkozás sikeres
        $this->urlRepositoryMock
            ->shouldReceive('create')
            ->with($originalUrl, $normalizedUrl, $secondShortCode)
            ->andReturn(Mockery::mock(\App\Models\Url::class));

        $this->urlGeneratorMock
            ->shouldReceive('to')
            ->with("/jump/{$secondShortCode}")
            ->andReturn($shortUrl);

        $result = $urlShortenerServiceMock->shortenUrl($originalUrl);

        $this->assertEquals($shortUrl, $result);
    }


    /**
     * Tests that shortenUrl throws an exception for an invalid URL.
     */
    public function testShortenUrlThrowsExceptionForInvalidUrl(): void
    {
        $invalidUrl = 'invalid-url';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid URL provided.');

        $this->urlShortenerService->shortenUrl($invalidUrl);
    }

    /**
     * Tests that generateShortCode produces a code of correct length and format.
     *
     * @throws \Exception
     */
    public function testGenerateShortCodeProducesValidCode(): void
    {
        $shortCode = $this->invokePrivateMethod($this->urlShortenerService, 'generateShortCode', []);

        $this->assertEquals(6, strlen($shortCode));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{6}$/', $shortCode);
    }

    /**
     * Tests that normalizeUrl correctly normalizes different URLs.
     */
    public function testNormalizeUrl(): void
    {
        $url = 'HTTPS://WWW.Example.COM/Path?query=123';
        $expectedNormalizedUrl = 'www.example.com/path?query=123';

        $normalizedUrl = $this->invokePrivateMethod($this->urlShortenerService, 'normalizeUrl', [$url]);

        $this->assertEquals($expectedNormalizedUrl, $normalizedUrl);
    }

    /**
     * Tests that isUniqueConstraintViolation correctly identifies unique constraint violations.
     */
    public function testIsUniqueConstraintViolation(): void
    {
        $pdoException = new \PDOException('Unique constraint violation');
        $pdoException->errorInfo = ['23000', 1062, 'Duplicate entry'];

        $queryException = new QueryException(
            '',
            '',
            [],
            $pdoException
        );

        $result = $this->invokePrivateMethod($this->urlShortenerService, 'isUniqueConstraintViolation', [$queryException]);

        $this->assertTrue($result);
    }


    /**
     * Invokes a private or protected method using reflection.
     *
     * @param object $object     The object instance.
     * @param string $methodName The name of the method.
     * @param array  $parameters The method parameters.
     *
     * @return mixed The method return value.
     */
    protected function invokePrivateMethod(object $object, string $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
