<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserAccessMenuItem extends Model
{

    protected $table = 'user_access_menu_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'role_id', 'menu_item_id',
    ];

}
