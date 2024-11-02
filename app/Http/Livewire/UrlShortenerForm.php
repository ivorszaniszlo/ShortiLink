<?php

/**
 * This file contains the UrlShortenerForm class.
 *
 * @category Livewire_Component
 * @package  App\Http\Livewire
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */

declare(strict_types=1);

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\UrlShortenerService;

/**
 * Livewire component for URL shortening form.
 *
 * This component allows users to input a long URL and receive a shortened URL.
 *
 * @category Livewire_Component
 * @package  App\Http\Livewire
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */
class UrlShortenerForm extends Component
{
    /**
     * The original URL input by the user.
     *
     * @var string
     */
    public string $originalUrl = '';

    /**
     * The resulting shortened URL.
     *
     * @var string|null
     */
    public ?string $shortenedUrl = null;

    /**
     * Validation rules for the URL input.
     *
     * @var array<string, string>
     */
    protected array $rules = [
        'originalUrl' => 'required|url',
    ];

    /**
     * Shortens the provided original URL.
     *
     * @param UrlShortenerService $urlShortenerService Service to handle URL shortening logic.
     *
     * @return void
     */
    public function shortenUrl(UrlShortenerService $urlShortenerService): void
    {
        $this->validate();

        // Use the service to shorten the URL
        $this->shortenedUrl = $urlShortenerService->shortenUrl($this->originalUrl);
    }

    /**
     * Renders the Livewire component view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.url-shortener-form');
    }
}
