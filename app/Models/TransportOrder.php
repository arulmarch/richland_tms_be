<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TransportOrder extends Model
{

    protected $table = 'tb_transport_order';

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
        'id', 'reference_id', 'manifest_id', 'trip', 'do_number', 'so_number', 'posting_date', 'delivery_date', 'req_arrival_date', 
        'document_date', 'order_type', 'origin_id', 'dest_id', 'client_id', 'order_status', 'order_qty', 'uom', 'order_qty_v2', 
        'uom_v2', 'remark', 'created_by', 'updated_by', 'created_date', 'updated_date', 'deleted', 'id_company'
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
