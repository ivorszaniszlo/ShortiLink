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
namespace App\Http\Controllers;

use App\Services\UrlShortenerService;
use Illuminate\Http\Request;

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
     * Show the form for creating a new URL.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('url.create');
    }

    /**
     * Store a new shortened URL.
     *
     * @param Request $request The HTTP request containing the original URL to shorten.
     * 
     * @return \Illuminate\Http\JsonResponse  JSON response containing the shortened URL.
     */
    public function store(Request $request)
    {
        $request->validate(['url' => 'required|url']);
        
        $shortenedUrl = $this->urlShortenerService->shortenUrl($request->input('url'));

        return response()->json(
            [
                'shortened_url' => $shortenedUrl
            ],
            201
        );
    }

      /**
       * Show details of the shortened URL by its ID.
       *
       * @param int $id The ID of the shortened URL.
       * 
       * @return \Illuminate\Http\JsonResponse
       */
    public function show(int $id)
    {
        $urlData = $this->urlShortenerService->getUrlDetailsById($id);

        if (!$urlData) {
            return response()->json(['error' => 'URL not found'], 404);
        }

        return response()->json(
            [
            'original_url' => $urlData->original_url,
            'short_code' => $urlData->short_code,
            'created_at' => $urlData->created_at
            ]
        );
    }


    /**
     * Redirect to the original URL based on the provided short code.
     *
     * @param string $code The unique short code to find the original URL.
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response  Redirects to the original URL or returns an error.
     */
    public function redirect(string $code)
    {
        $url = $this->urlShortenerService->findOriginalUrl($code);

        if (!$url) {
            return response()->json(['error' => 'URL not found'], 404);
        }

        return redirect()->away($url);
    }
}
