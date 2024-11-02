<?php

/**
 * Migration to create the 'urls' table.
 *
 * This migration defines the schema for storing URLs and their associated
 * short codes.
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
 * Class CreateUrlsTable
 *
 * Migration class responsible for creating the 'urls' table.
 * 
 * @category Migration
 * @package  Database\Migrations
 * @author   Szaniszló Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */
class CreateUrlsTable extends Migration
{
    /**
     * Run the migrations to create the 'urls' table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'urls',
            function (Blueprint $table) {
                $table->id();
                $table->string('original_url');
                $table->string('normalized_url')->unique();
                $table->string('short_code')->unique();
                $table->timestamps();
            }
        );        
    }


    /**
     * Reverse the migrations by dropping the 'urls' table.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
}
