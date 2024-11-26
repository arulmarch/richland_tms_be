<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{

    protected $table = 'tb_vehicles';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; // disable all behavior

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_date = $model->freshTimestamp();
        });
        static::updating(function ($model) {
            $model->updated_date = $model->freshTimestamp();
        });
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'vehicle_id', 'driver', 'co_driver', 'transporter_id', 'status', 'type', 'max_volume', 'max_weight', 'subcon', 
        'additional_information', 'created_by', 'updated_by', 'created_date', 'updated_date', 'deleted', 'id_company','no_stnk',
        'no_kir','tgl_aktif_stnk','tgl_aktif_kir','no_lambung','foto_stnk', 'foto_kir'
    ];


    /**
     * Customize timestamp.
     */
    public function getCreatedAtAttribute($date) {
        $this->attributes['created_date'] = $date;

        $date = new \Carbon\Carbon($date);
        return $date->setTimezone('GMT+7')->format('Y-m-d H:i:s');
    }
    public function getUpdatedAtAttribute($date) {
        $this->attributes['updated_date'] = $date;

        $date = new \Carbon\Carbon($date);
        return $date->setTimezone('GMT+7')->format('Y-m-d H:i:s');
    }

}
