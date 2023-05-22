<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTable extends Model
{
    use HasFactory;

    protected $table = 'orderIteams';

    protected $fillable = [
        'order_id',
        'name',
        'total_quantity',
        'base_price',
        'total_price',
    ];



}
