<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const CUSTOMER = 'CUSTOMER';
    public const SELLER = 'SELLER';
    protected $table = 'role';

    protected $fillable = [
        'name',
    ];

    public function users() {
        return $this->hasMany('User', 'role_id');
    }
}
