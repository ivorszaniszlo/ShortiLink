<?php
declare(strict_types=1);
/**
 * This file contains the UrlShortenerService class, which is responsible for the business logic of URL shortening.
 *
 * @category Service
 * @package  App\Services
 * @author   Szaniszló Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */

namespace App\Services;

use App\Repositories\UrlRepository;

/**
 * Class UrlShortenerService
 *
 * This service handles the business logic of shortening URLs.
 *
 * @category Service
 * @package  App\Services
 * @author   Szaniszló Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */
class UrlShortenerService
{
    protected UrlRepository $urlRepository;

    /**
     * UrlShortenerService constructor.
     *
     * @param UrlRepository $urlRepository The repository responsible for URL data access.
     */
    public function __construct(UrlRepository $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }

    /**
     * Generate a unique short code for a given URL.
     * This method ensures that the generated code does not collide with existing entries.
     *
     * @param string $url The original URL to generate a short code for.
     *
     * @return string The generated unique short code.
     */
    private function _generateShortCode(string $url): string
    {
        do {
            $shortCode = substr(bin2hex(random_bytes(3)), 0, 6);
        } while ($this->urlRepository->existsByCode($shortCode));

        return $shortCode;
    }

    /**
     * Shorten a given URL.
     *
     * @param string $url The original URL to shorten.
     *
     * @return string The generated short URL.
     */
    public function shortenUrl(string $url): string
    {
        $shortCode = $this->_generateShortCode($url);
        $this->urlRepository->create($url, $shortCode);
        return "/jump/{$shortCode}";
    }
}
