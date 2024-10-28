<?php

/**
 * This file contains the Url model, which represents a URL entity in the application.
 * It is responsible for interacting with the "urls" database table.
 *
 * @category Model
 * @package  App\Models
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @version  PHP 8.2
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */

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
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @version  PHP 8.2
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */
class Url extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'urls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'original_url',
        'short_code',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
