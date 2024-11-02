<div x-data="{ copied: false }">
    <form wire:submit.prevent="shortenUrl">
        <input type="text" wire:model="originalUrl" placeholder="Enter URL to shorten">
        @error('originalUrl') <span class="error">{{ $message }}</span> @enderror
        <button type="submit">Shorten URL</button>
    </form>

    @if ($shortenedUrl)
        <p>Shortened URL: <a href="{{ $shortenedUrl }}" target="_blank">{{ $shortenedUrl }}</a></p>
        <button
            @click="navigator.clipboard.writeText('{{ $shortenedUrl }}'); copied = true; setTimeout(() => copied = false, 2000)"
        >
            Copy
        </button>
        <span x-show="copied" class="success">Copied!</span>
    @endif

    <style>
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        button {
            margin-top: 10px;
        }
    </style>
</div>
