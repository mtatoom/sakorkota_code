<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domaine extends Model
{
   protected $connection = 'mysql';

    public $timestamps = false;

    protected $fillable = ['domaine', 'tenant_id'];

    /**
     * Relation inverse : Ce domaine appartient à un client précis.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
