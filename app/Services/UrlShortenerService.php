<?php

/**
 * This file contains the UrlShortenerService class, which is responsible for the business logic of URL shortening.
 *
 * @category Service
 * @package  App\Services
 * @author   Szaniszló Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @version  PHP 8.2
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
     * Shorten a given URL.
     *
     * @param string $url The original URL to shorten.
     *
     * @return string The generated short code.
     */
    public function shortenUrl(string $url): string
    {
        // Implement the URL shortening logic here
        // For example, generate a hash or a unique identifier for the URL
        $shortCode = substr(md5($url), 0, 6);
        return $shortCode;
    }
}
