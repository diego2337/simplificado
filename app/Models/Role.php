<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function users() {
        return $this->hasMany('User', 'role_id');
    }
}