<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\MasterclassFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property Carbon $mc_date
 * @property-read User $leader
 * @property-read CraftCategory $category
 */
class Masterclass extends Model
{
    /** @use HasFactory<MasterclassFactory> */
    use HasFactory;

    protected $fillable = [
        'leader_id',
        'category_id',
        'title',
        'description',
        'mc_date',
        'start_time',
        'max_participants',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'mc_date' => 'date',
            'price' => 'decimal:2',
        ];
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CraftCategory::class, 'category_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function bookedCount(): int
    {
        return $this->bookings()->count();
    }

    public function freePlaces(): int
    {
        return max(0, $this->max_participants - $this->bookedCount());
    }
}
