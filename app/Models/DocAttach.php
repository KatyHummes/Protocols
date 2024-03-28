<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocAttach extends Model
{
    use HasFactory;

    protected $fillable = [
        'protocol_id',
        'file',
    ];

    public function protocol()
    {
        return $this->belongsTo(Protocol::class);
    }
}
