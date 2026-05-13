<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $connection = 'mysql_secondaire';
    protected $fillable = ['order_number', 'customer_id', 'user_id', 'total_excl_tax', 'total_incl_tax', 'delivery_fees', 'payment_status', 'delivery_status', 'payment_method', 'sale_date'];

    public function items() { return $this->hasMany(SaleItem::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
}
