<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'protocol_id',
        'description',
        'status',
    ];

    public function protocol()
    {
        return $this->belongsTo(Protocol::class);
    }
}
