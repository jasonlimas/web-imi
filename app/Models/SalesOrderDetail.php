<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrderDetail extends Model
{
    use SoftDeletes;

    protected $fillable = ['so_mstr_id', 'line', 'item_id', 'qty', 'price', 'total'];

    public function item()
    {
        return $this->belongsTo(MasterItem::class, 'item_id');
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'so_mstr_id');
    }
}
