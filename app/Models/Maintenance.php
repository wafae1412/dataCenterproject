<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
    protected $fillable = [
        'resource_id',
        'title',
        'description',
        'type',
        'start_date',
        'end_date',
        'estimated_duration',
        'notes',
        'status',


    ];
    // AJOUTER CES CASTS
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
     public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
