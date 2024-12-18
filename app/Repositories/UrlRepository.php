<?php

/**
 * This file contains the UrlRepository class, which is responsible for handling database operations related to URLs.
 *
 * @category Repository
 * @package  App\Repositories
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Url;

/**
 * Class UrlRepository
 *
 * This repository is responsible for handling database operations related to URLs.
 *
 * @category Repository
 * @package  App\Repositories
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */
class UrlRepository
{
    /**
     * Create a new URL record in the database.
     *
     * @param string $originalUrl   The original URL.
     * @param string $normalizedUrl The normalized version of the original URL.
     * @param string $code          The short code to associate with the URL.
     *
     * @return Url The newly created URL model instance.
     */
    public function create(string $originalUrl, string $normalizedUrl, string $code): Url
    {
        return Url::create(
            [
            'original_url'   => $originalUrl,
            'normalized_url' => $normalizedUrl,
            'short_code'     => $code,
            ]
        );
    }

    /**
     * Check if a short code already exists in the database.
     *
     * @param string $code The short code to check.
     *
     * @return bool True if the code exists, false otherwise.
     */
    public function existsByCode(string $code): bool
    {
        return $this->_findByColumn('short_code', $code) !== null;
    }

    /**
     * Find the original URL by short code.
     *
     * @param string $code The short code.
     *
     * @return string|null The original URL, or null if not found.
     */
    public function findOriginalUrlByShortCode(string $code): ?string
    {
        $urlRecord = $this->_findByColumn('short_code', $code);

        return $urlRecord?->original_url;
    }

    /**
     * Find a URL record by normalized URL.
     *
     * @param string $normalizedUrl The normalized URL.
     *
     * @return Url|null The URL model instance, or null if not found.
     */
    public function getUrlByNormalizedUrl(string $normalizedUrl): ?Url
    {
        return $this->_findByColumn('normalized_url', $normalizedUrl);
    }

    /**
     * Find a URL by a specific column.
     *
     * @param string $column The column to search by.
     * @param string $value  The value to search for.
     *
     * @return Url|null The URL model instance, or null if not found.
     */
    private function _findByColumn(string $column, string $value): ?Url
    {
        return Url::where($column, $value)->first();
    }
}
