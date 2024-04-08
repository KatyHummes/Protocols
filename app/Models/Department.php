<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'access', 'department_id', 'user_id');
    }

    public function protocols()
    {
        return $this->hasMany(Protocol::class);
    }
}
