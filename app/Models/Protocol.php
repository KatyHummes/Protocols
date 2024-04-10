<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Protocol extends Model
{
    use HasFactory;
    protected $fillable = [
        'department_id', 
        'people_id',
        'description',
        'date',
        'term',
    ];

    public function docattachs()
    {
        return $this->hasMany(DocAttach::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function people()
    {
        return $this->belongsTo(People::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
