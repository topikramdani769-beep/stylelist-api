<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WardrobeItem extends Model
{
    protected $fillable = [
        'user_id',
        'outfit_id',
        'title',
        'notes',
        'price',
        'color',
        'image_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function outfit()
    {
        return $this->belongsTo(Outfit::class);
    }
}