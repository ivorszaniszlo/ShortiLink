<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shorten URL</title>

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Alpine.js should only be loaded in app.blade.php to avoid duplication -->
</head>
<body>
    <!-- Livewire Component -->
    @livewire('url-shortener-form')

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
