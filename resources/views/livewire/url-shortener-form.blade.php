<div>
    <form wire:submit.prevent="shortenUrl">
        <input type="text" wire:model="url" placeholder="Enter URL to shorten">
        <button type="submit">Shorten URL</button>
    </form>

    @if ($shortenedUrl)
        <p>Shortened URL: <a href="{{ $shortenedUrl }}">{{ $shortenedUrl }}</a></p>
    @endif
</div>
