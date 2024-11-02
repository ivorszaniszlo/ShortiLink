<?php
declare(strict_types=1);
/**
 * This file contains the UrlRepository class, which is responsible for handling database operations related to URLs.
 *
 * @category Repository
 * @package  App\Repositories
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */

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
     * @param string $url  The original URL.
     * @param string $code The short code to associate with the URL.
     *
     * @return \App\Models\Url The newly created URL model instance.
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
        return Url::where('short_code', $code)->exists();
    }

    /**
     * Find the original URL by short code.
     *
     * @param string $code The short code.
     * 
     * @return string|null The original URL, or null if not found.
     */
    public function findUrlByCode(string $code): ?string
    {
        $urlRecord = Url::where('short_code', $code)->first();

        return $urlRecord?->original_url;
    }

    public function findByNormalizedUrl(string $normalizedUrl): ?Url
    {
        return Url::where('normalized_url', $normalizedUrl)->first();
    }
}
