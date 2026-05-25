<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CraftCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CraftCategory extends Model
{
    /** @use HasFactory<CraftCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
    ];

    public function masterclasses(): HasMany
    {
        return $this->hasMany(Masterclass::class, 'category_id');
    }
}
