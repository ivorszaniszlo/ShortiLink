<?php

/**
 * URL Shortener Service responsible for shortening URLs and retrieving original URLs.
 *
 * @category Service
 * @package  App\Services
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UrlRepository;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Routing\UrlGenerator;
use InvalidArgumentException;

/**
 * Class UrlShortenerService
 *
 * Handles the shortening of URLs and retrieval of the original URLs.
 * 
 * @category Service
 * @package  App\Services
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */
class UrlShortenerService
{
    /**
     * The repository responsible for URL database operations.
     *
     * @var UrlRepository
     */
    private UrlRepository $_urlRepository;

    /**
     * The URL generator service.
     *
     * @var UrlGenerator
     */
    private UrlGenerator $_urlGenerator;

    private const UNIQUE_CONSTRAINT_VIOLATION_CODE = '23000';

    /**
     * UrlShortenerService constructor.
     *
     * @param UrlRepository $urlRepository The repository handling URL operations.
     * @param UrlGenerator  $urlGenerator  The URL generator service.
     */
    public function __construct(UrlRepository $urlRepository, UrlGenerator $urlGenerator)
    {
        $this->_urlRepository = $urlRepository;
        $this->_urlGenerator = $urlGenerator;
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
        $this->_validateUrl($url);

        $normalizedUrl = $this->_normalizeUrl($url);

        // Check if the URL already exists
        $existingUrl = $this->_urlRepository->getUrlByNormalizedUrl($normalizedUrl);
        if ($existingUrl) {
            return $this->_urlGenerator->to("/jump/{$existingUrl->short_code}");
        }

        // Generate and save short code
        do {
            $shortCode = $this->generateShortCode();

            try {
                $this->_urlRepository->create($url, $normalizedUrl, $shortCode);
                $success = true;
            } catch (QueryException $e) {
                if ($this->_isUniqueConstraintViolation($e)) {
                    $success = false;
                } else {
                    throw $e;
                }
            }
        } while (!$success);

        return $this->_urlGenerator->to("/jump/{$shortCode}");
    }

    /**
     * Validates the given URL.
     *
     * @param string $url The URL to validate.
     * 
     * @return void
     * 
     * @throws InvalidArgumentException If the provided URL is invalid.
     */
    private function _validateUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid URL provided.');
        }
    }

    /**
     * Normalizes the URL for comparison.
     *
     * @param string $url The URL to normalize.
     *
     * @return string The normalized URL.
     */
    private function _normalizeUrl(string $url): string
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
    private function _isUniqueConstraintViolation(QueryException $e): bool
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
        return $this->_urlRepository->findOriginalUrlByShortCode($code);
    }
}
