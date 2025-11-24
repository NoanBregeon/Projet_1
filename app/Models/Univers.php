<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Univers
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string|null $image
 * @property string|null $logo
 * @property string $primary_color
 * @property string $secondary_color
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $favoriteUsers
 * @property-read int|null $favorite_users_count
 *
 * @method static \Database\Factories\UniversFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Univers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Univers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Univers query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Univers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Univers whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Univers whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Univers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Univers whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Univers whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Univers whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Univers wherePrimaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Univers whereSecondaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Univers whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Univers extends Model
{
    use HasFactory;

    protected $table = 'univers';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'logo',
        'primary_color',
        'secondary_color',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * Users who have this univers as favorite.
     */
    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    /**
     * Helper to count favorites.
     */
    public function favoritesCount(): int
    {
        return $this->favoriteUsers()->count();
    }
}
