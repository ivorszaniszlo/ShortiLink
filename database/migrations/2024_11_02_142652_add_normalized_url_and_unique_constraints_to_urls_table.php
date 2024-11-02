<?php

/**
 * This file contains the AddNormalizedUrlAndUniqueConstraintsToUrlsTable class.
 *
 * @category Migration
 * @package  Database\Migrations
 * @author   Szaniszló Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink  
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddNormalizedUrlAndUniqueConstraintsToUrlsTable
 *
 * Migration class responsible for adding a new column for normalized URLs and unique constraints to the 'urls' table.
 * 
 * @category Migration
 * @package  Database\Migrations
 * @author   Szaniszló Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink 
 */
class AddNormalizedUrlAndUniqueConstraintsToUrlsTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds a new column for normalized URLs and unique constraints to the 'urls' table.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table(
        //     'urls',
        //     function (Blueprint $table) {
        //         $table->string('normalized_url')
        //             ->unique()
        //             ->after('original_url');

        //         // $table->unique('short_code');
        //     }
        // );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table(
        //     'urls',
        //     function (Blueprint $table) {
        //         $table->dropUnique(['normalized_url']);
        //         $table->dropColumn('normalized_url');

        //         // $table->dropUnique(['short_code']);
        //     }
        // );
    }
}
