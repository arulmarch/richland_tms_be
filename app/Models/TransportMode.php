<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TransportMode extends Model
{

    protected $table = 'tb_transport_mode';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'transport_mode', 'desc'
    ];

}
