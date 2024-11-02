<?php
declare(strict_types=1);

/**
 * URL Shortener Service responsible for shortening URLs and retrieving original URLs.
 *
 * @category Service
 * @package  App\Services
 */

namespace App\Services;

use App\Repositories\UrlRepository;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Routing\UrlGenerator;
use InvalidArgumentException;

/**
 * Class UrlShortenerService
 *
 * Handles the shortening of URLs and retrieval of the original URLs.
 */
class UrlShortenerService
{
    /**
     * The repository responsible for URL database operations.
     *
     * @var UrlRepository
     */
    protected UrlRepository $urlRepository;

    /**
     * The URL generator service.
     *
     * @var UrlGenerator
     */
    protected UrlGenerator $urlGenerator;

    private const UNIQUE_CONSTRAINT_VIOLATION_CODE = '23000';



    /**
     * UrlShortenerService constructor.
     *
     * @param UrlRepository $urlRepository The repository handling URL operations.
     * @param UrlGenerator  $urlGenerator  The URL generator service.
     */
    public function __construct(UrlRepository $urlRepository, UrlGenerator $urlGenerator)
    {
        $this->urlRepository = $urlRepository;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Shortens the given URL and returns the shortened URL.
     *
     * @param string $url The URL to shorten.
     *
     * @return string The shortened URL.
     *
     * @throws InvalidArgumentException If the provided URL is invalid.
     */
    public function shortenUrl(string $url): string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid URL provided.');
        }

        $normalizedUrl = $this->normalizeUrl($url);

        // Check if the URL already exists
        $existingUrl = $this->urlRepository->findByNormalizedUrl($normalizedUrl);
        if ($existingUrl) {
            return $this->urlGenerator->to("/jump/{$existingUrl->short_code}");
        }

        // Generate and save short code
        do {
            $shortCode = $this->generateShortCode();

            try {
                $this->urlRepository->create($url, $normalizedUrl, $shortCode);
                $success = true;
            } catch (QueryException $e) {
                if ($this->isUniqueConstraintViolation($e)) {
                    $success = false; // Collision occurred, try again
                } else {
                    throw $e;
                }
            }
        } while (!$success);

        return $this->urlGenerator->to("/jump/{$shortCode}");
    }

    /**
     * Normalizes the URL for comparison.
     *
     * @param string $url The URL to normalize.
     *
     * @return string The normalized URL.
     */
    private function normalizeUrl(string $url): string
    {
        $parsedUrl = parse_url(strtolower($url));

        $host = $parsedUrl['host'] ?? '';
        $path = $parsedUrl['path'] ?? '';
        $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';

        return $host . $path . $query;
    }

    /**
     * Generates a short code.
     *
     * @return string The generated short code.
     *
     * @throws \Exception If random bytes generation fails.
     */
    protected function generateShortCode(): string
    {
        return substr(bin2hex(random_bytes(3)), 0, 6);
    }

    /**
     * Checks if the database exception is due to a unique constraint violation.
     *
     * @param QueryException $e The database exception.
     *
     * @return bool True if a unique constraint violation occurred.
     */
    private function isUniqueConstraintViolation(QueryException $e): bool
    {
        $sqlStateCode = $e->errorInfo[0] ?? null;
        return $sqlStateCode === self::UNIQUE_CONSTRAINT_VIOLATION_CODE;
    }


    /**
     * Retrieves the original URL based on the short code.
     *
     * @param string $code The short code.
     *
     * @return string|null The original URL or null if not found.
     */
    public function findOriginalUrl(string $code): ?string
    {
        return $this->urlRepository->findUrlByCode($code);
    }
}
