<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrder extends Model
{
    use SoftDeletes;

    protected $fillable = ['so_nbr', 'so_cust', 'so_ord_date', 'so_due_date', 'so_status', 'created_by'];

    public function customer()
    {
        return $this->belongsTo(MasterCustomer::class, 'so_cust');
    }

    public function details()
    {
        return $this->hasMany(SalesOrderDetail::class, 'so_mstr_id');
    }
}
