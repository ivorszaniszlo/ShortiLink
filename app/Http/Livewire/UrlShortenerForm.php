<?php
declare(strict_types=1);
/**
 * This file contains the UrlShortenerForm class.
 *
 * @category Livewire_Component
 * @package  App\Http\Livewire
 * @author   Szaniszló Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */
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
 * @author   Szaniszló Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */
class UrlShortenerForm extends Component
{
    /**
     * The URL to be shortened.
     *
     * @var string
     */
    public string $url = '';

    /**
     * The shortened URL result.
     *
     * @var string|null
     */
    public ?string $shortenedUrl = null;

    /**
     * Validation rules for the URL input.
     *
     * @var array
     */
    protected $rules = [
        'url' => 'required|url'
    ];

    protected UrlShortenerService $urlShortenerService;

    /**
     * Shortens the provided URL.
     *
     * @param UrlShortenerService $urlShortenerService Service to shorten URLs.
     * 
     * @return void
     */
    public function shortenUrl()
    {
        $this->validate();

        $urlShortenerService = app(UrlShortenerService::class);
        $this->shortenedUrl = $urlShortenerService->shortenUrl($this->url);
    }

    /**
     * Renders the Livewire component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.url-shortener-form')
            ->layout('components.layouts.app');
    }

}
