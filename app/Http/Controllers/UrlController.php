<?php

/**
 * This file contains the UrlController class, which is responsible for handling URL shortening and redirection.
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\UrlShortenerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

/**
 * Controller for managing URL shortening and redirection.
 *
 * This controller provides methods for storing a new shortened URL and
 * redirecting to the original URL based on a short code.
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */
class UrlController extends Controller
{
    /**
     * Service for handling URL shortening and retrieval.
     *
     * @var UrlShortenerService
     */
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
     * @return RedirectResponse|Response Redirects to the original URL or returns a 404 error if not found.
     */
    public function redirectToOriginalUrl(string $code)
    {
        $originalUrl = $this->_getOriginalUrl($code);

        return redirect()->away($originalUrl);
    }

    /**
     * Retrieves the original URL based on the short code.
     *
     * @param string $code The short code associated with the original URL.
     *
     * @return string The original URL.
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException If no URL is found.
     */
    private function _getOriginalUrl(string $code): string
    {
        $url = $this->urlShortenerService->findOriginalUrl($code);

        if (!$url) {
            abort(404, 'The requested URL could not be found.');
        }

        return $url;
    }
}
