<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TransporterRate extends Model
{

    protected $table = 'tb_transporter_rates';

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
        'id', 'rate_status', 'client_id', 'transporter_id', 'origin_id', 'destination_id', 'type_id', 'status', 'currency', 
        'rate_type', 'vehicle_rate', 'min_weight', 'remark', 'created_by', 'updated_by', 'created_date', 'updated_date', 'deleted', 'id_company'
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