<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TruckingOrder extends Model
{

    protected $table = 'tb_trucking_order';

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
        'id', 'client_id', 'schedule_date', 'transport_mode', 'budget', 'pref_vehicle_type', 'tr_status', 'origin_id', 'dest_id', 'origin_area_id',
        'dest_area_id', 'req_pickup_time', 'req_arrival_time', 'created_by', 'updated_by', 'created_date', 'updated_date', 'deleted', 'id_company'
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
