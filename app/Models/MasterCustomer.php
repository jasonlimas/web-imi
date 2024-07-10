<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'cust_code',
        'cust_desc',
        'cust_addr'
    ];
}
