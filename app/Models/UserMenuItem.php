<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserMenuItem extends Model
{

    protected $table = 'user_menu_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'menu_id', 'page_name', 'title', 'url', 'sequence', 'description', 'is_active', 'icon',
    ];

}
