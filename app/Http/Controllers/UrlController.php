<?php

/**
 * This file contains the UrlController class, which is responsible for handling URL shortening and redirection.
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Szaniszló Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\UrlShortenerService;

/**
 * Controller for managing URL shortening and redirection.
 *
 * This controller provides methods for storing a new shortened URL and
 * redirecting to the original URL based on a short code.
 *
 * UrlController handles URL shortening and redirection.
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Szaniszló Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */
class UrlController extends Controller
{
    protected UrlShortenerService $urlShortenerService;

    /**
     * Constructor to initialize UrlShortenerService.
     *
     * @param UrlShortenerService $urlShortenerService Service for handling URL shortening and retrieval.
     */
    public function __construct(UrlShortenerService $urlShortenerService)
    {
        $this->urlShortenerService = $urlShortenerService;
    }

    /**
     * Redirect to the original URL based on the provided short code.
     *
     * @param string $code The unique short code to find the original URL.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response Redirects to the original URL or returns
     *  an error.
     */
    public function redirect(string $code)
    {
        $url = $this->urlShortenerService->findOriginalUrl($code);

        if (!$url) {
            abort(404);
        }

        return redirect()->away($url);
    }
}
