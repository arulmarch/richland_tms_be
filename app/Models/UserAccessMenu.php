<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserAccessMenu extends Model
{

    protected $table = 'user_access_menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'role_id', 'menu_id',
    ];

}
