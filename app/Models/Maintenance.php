<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
    protected $fillable = [
        'resource_id',
        'description',
        'date_start',
        'date_end'
    ];
     public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
    
}
