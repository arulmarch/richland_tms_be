<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserAccessSubMenu extends Model
{

    protected $table = 'user_access_sub_menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'role_id', 'sub_menu_id',
    ];

}
