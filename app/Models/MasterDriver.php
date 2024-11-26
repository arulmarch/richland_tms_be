<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MasterDriver extends Model
{

    protected $table = 'tb_drivers';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'address1', 'address2', 'city', 'phone', 'transporter_id', 'created_by', 
        'updated_by', 'created_date', 'updated_date', 'deleted', 'id_company', 'image','foto_sim','foto_ktp'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'token', 'token_fcm'
    ];


    /**
     * Customize timestamp.
     */
    // public function getCreatedAtAttribute($date) {
    //     $date = new \Carbon\Carbon($date);
    //     return $date->setTimezone('GMT+7')->format('Y-m-d H:i:s');
    // }
    // public function getUpdatedAtAttribute($date) {
    //     $date = new \Carbon\Carbon($date);
    //     return $date->setTimezone('GMT+7')->format('Y-m-d H:i:s');
    // }

}
