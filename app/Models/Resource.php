<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;
     protected $fillable = [
        'name',
        'category_id',
        'status',
        'description',
        'cpu',
        'ram',
        'storage',
        'location'
    ];
    public function category()
{
    return $this->belongsTo(Category::class);
}

public function reservations()
{
    return $this->hasMany(Reservation::class);
}

public function maintenances()
{
    return $this->hasMany(Maintenance::class);
}

}
