<?php

/**
 * This file contains the Url model, which represents a URL entity in the application.
 * It is responsible for interacting with the "urls" database table.
 *
 * @category Model
 * @package  App\Models
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
/**
 * Represents a shortened URL entity.
 * 
 * @property int $id
 * @property string $original_url
 * @property string $normalized_url
 * @property string $short_code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @category Model
 * @package  App\Models
 * @author   Szaniszlo Ivor <szaniszlo.ivor@gmail.com>
 * @license  MIT License
 * @link     https://github.com/ivorszaniszlo/ShortiLink
 */
class Url extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'original_url',
        'normalized_url',
        'short_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope a query to find a URL by its short code.
     *
     * @param Builder $query     The query builder instance.
     * @param string  $shortCode The short code to search for.
     * 
     * @return Builder
     */
    public function scopeFindByShortCode(Builder $query, string $shortCode): Builder
    {
        return $query->where('short_code', $shortCode);
    }
}
