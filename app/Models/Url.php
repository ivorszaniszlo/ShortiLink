<?php

/**
 * This file contains the Url model, which represents a URL entity in the application.
 * It is responsible for interacting with the "urls" database table.
 *
 * @category Model
 * @package  App\Models
 * @author   Szaniszlo Ivor
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Url
 *
 * The Url model represents a URL entity in the application and is responsible for interacting
 * with the "urls" database table.
 *
 * @category Model
 * @package  App\Models
 * @author   Szaniszlo Ivor
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */
class Url extends Model
{
    protected $table = 'urls';

    protected $fillable = [
        'original_url',
        'normalized_url',
        'short_code',
    ];
}
