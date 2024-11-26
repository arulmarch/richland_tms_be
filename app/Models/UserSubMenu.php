<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserSubMenu extends Model
{

    protected $table = 'user_sub_menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'menu_item_id', 'page_name', 'title', 'url', 'sequence', 'description', 'is_active', 'icon',
    ];

}
