<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $connection = 'mysql_secondaire';

    protected $fillable = ['name', 'phone', 'email', 'address', 'total_spent'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
