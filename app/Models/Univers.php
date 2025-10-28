<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Univers extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Relation many-to-many avec les utilisateurs qui ont mis en favori
    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    // Compter le nombre de favoris
    public function favoritesCount()
    {
        return $this->favoriteUsers()->count();
    }
}
