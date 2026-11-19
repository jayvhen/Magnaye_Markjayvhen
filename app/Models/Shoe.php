<?php

namespace App\Models;

use App\Http\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A shoe that belongs to a user (its author).
 * Fillable lists the columns that may be mass assigned with Shoe::create([...]).
 */
#[Fillable(['shoe', 'description', 'status', 'user_id'])]
class Shoe extends Model
{
    // Gives the model access to factories (used by seeders and tests).
    use HasFactory;

    /**
     * The user who wrote the shoe. Each shoe belongs to one user,
     * found through the user_id column.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Query scope: Shoe::filter($filters) applies the filters sent in the URL.
     */
    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }
}
