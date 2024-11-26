<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Manifest extends Model
{

    protected $table = 'tb_manifests';

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
        'id', 'vehicle_id', 'tr_id', 'start', 'schedule_date', 'trip', 'shipment_type', 'finish', 'mileage', 'mode',
        'manifest_status', 'fixed_cost', 'variable_cost', 'client_variable_cost', 'component_entries', 'sum_component_cost',
        'load_m3', 'load_kg', 'invsal_hash_id', 'invpch_hash_id', 'driver_id', 'co_driver_id', 'order_cash', 'approved_by', 
        'created_by', 'created_date', 'updated_by', 'updated_date', 'deleted', 'id_company', 'file_name'
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
