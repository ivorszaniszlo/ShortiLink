<div>
    <form wire:submit.prevent="shortenUrl">
        <input type="text" wire:model="url" placeholder="Enter URL to shorten">
        @error('url') <span class="error">{{ $message }}</span> @enderror
        <button type="submit">Shorten URL</button>
    </form>

    @if ($shortenedUrl)
        <p>Shortened URL: <a href="{{ $shortenedUrl }}">{{ $shortenedUrl }}</a></p>
        <button x-data @click="navigator.clipboard.writeText('{{ $shortenedUrl }}')">Copy</button>
    @endif

</div>
