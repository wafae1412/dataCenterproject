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
        'cpu',
        'ram',
        'storage',
        'status',
        'description',
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

    // Constantes pour les états des ressources
    const STATUS_AVAILABLE = 'available';
    const STATUS_RESERVED = 'reserved';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_DISABLED = 'disabled';

    // Vérifier si la ressource est disponible
    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE;
    }
}
