<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MasterCustomer extends Model
{

    protected $table = 'tb_customers';

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
        'id', 'customer_id', 'name', 'address1', 'address2', 'type', 'city', 'position', 'region_id', 
        'area_id', 'postal_code', 'phone', 'fax', 'pic', 'email', 'additional_information', 'created_by', 
        'updated_by', 'created_date', 'updated_date', 'deleted', 'id_company'
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
