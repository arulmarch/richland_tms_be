<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserMenu extends Model
{

    protected $table = 'user_menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'page_name', 'title', 'url', 'sequence', 'description', 'is_active', 'icon',
    ];

}
