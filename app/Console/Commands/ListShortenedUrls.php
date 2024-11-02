<?php

/**
 * This file contains the ListShortenedUrls Artisan command.
 * 
 * @category Console_Commands
 * @package  App\Console\Commands
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Url;

/**
 * Class ListShortenedUrls
 *
 * This Artisan command lists all shortened URLs stored in the system.
 *
 * @category Console_Commands
 * @package  App\Console\Commands
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */
class ListShortenedUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'urls:list {--search= : Search for URLs containing this string}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all shortened URLs in the system';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $search = $this->option('search');
        $urls = $this->_getUrls($search);

        if ($urls->isEmpty()) {
            $this->info('No shortened URLs found in the system.');
            return Command::SUCCESS;
        }

        $this->_displayUrlsInTable($urls);
        return Command::SUCCESS;
    }

    /**
     * Retrieves the list of URLs, optionally filtered by a search term.
     *
     * @param string|null $search The search term to filter the URLs.
     *
     * @return \Illuminate\Support\Collection The collection of URLs.
     */
    private function _getUrls(?string $search)
    {
        $query = Url::query();

        if (!empty($search)) {
            $query->where('original_url', 'like', "%{$search}%")
                ->orWhere('short_code', 'like', "%{$search}%");
        }

        return $query->get(['id', 'original_url', 'short_code', 'created_at']);
    }

    /**
     * Displays the URLs in a table format.
     *
     * @param \Illuminate\Support\Collection $urls The collection of URLs to display.
     *
     * @return void
     */
    private function _displayUrlsInTable($urls): void
    {
        $tableData = $urls->map(
            fn (Url $url) => [
                'ID'           => $url->id,
                'Original URL' => $url->original_url,
                'Short Code'   => $url->short_code,
                'Created At'   => $url->created_at->format('Y-m-d H:i:s'),
            ]
        )->toArray();

        $this->table(['ID', 'Original URL', 'Short Code', 'Created At'], $tableData);
    }
}
