<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \App\Models\ItemTable;
class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $timestamps = false;

    protected $table = 'movements';

    protected $fillable = [
        'order_id',
        'date',
        'name',
        'quantity',
        'price',
    ];
    public function order_item()
    {

        return $this->hasMany(ItemTable::class,'order_id','order_id');
    }




}
