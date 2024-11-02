<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Url;

/**
 * Class ListShortenedUrls
 *
 * This Artisan command lists all shortened URLs stored in the system.
 *
 * @category Console Commands
 * @package  App\Console\Commands
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
     * Retrieves and displays all shortened URLs in a table format.
     * Optionally filters the URLs based on the provided search term.
     *
     * @return int
     */
    public function handle(): int
    {
        // Retrieve the search option if provided
        $search = $this->option('search');

        // Initialize the query builder for the Url model
        $query = Url::query();

        // Apply search filter if the search option is provided
        if ($search) {
            $query->where('original_url', 'like', "%{$search}%")
                  ->orWhere('short_code', 'like', "%{$search}%");
        }

        // Execute the query and retrieve the relevant fields
        $urls = $query->get(['id', 'original_url', 'short_code', 'created_at']);

        // Check if any URLs were found
        if ($urls->isEmpty()) {
            $this->info('No shortened URLs found in the system.');
            return Command::SUCCESS;
        }

        // Prepare the data for the table display
        $tableData = $urls->map(function (Url $url) {
            return [
                'ID'           => $url->id,
                'Original URL' => $url->original_url,
                'Short Code'   => $url->short_code,
                'Created At'   => $url->created_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        // Display the URLs in a table format
        $this->table(
            ['ID', 'Original URL', 'Short Code', 'Created At'],
            $tableData
        );

        return Command::SUCCESS;
    }
}
