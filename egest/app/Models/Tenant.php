<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $connection = 'mysql';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['id', 'name', 'db_name', 'db_username', 'db_password', 'subscription_plan', 'is_active'];

    public function domains() { return $this->hasMany(Domain::class); }
}
