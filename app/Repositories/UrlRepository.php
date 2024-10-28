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
    public function create(string $url, string $code): Url
    {
        return Url::create(
            [
                'original_url' => $url,
                'short_code'   => $code,
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
}
