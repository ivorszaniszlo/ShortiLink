<form action="{{ route('url.store') }}" method="POST">
    @csrf
    <label for="url">Enter URL to shorten:</label>
    <input type="url" name="url" id="url" required>
    <button type="submit">Shorten URL</button>
</form>
