<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class HistoryChangeLoad extends Model
{

    protected $table = 'tb_history_change_load';

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
        'id', 'old_load', 'new_load', 'desc', 'created_by', 'created_date', 'deleted', 'id_manifest', 
        'id_company', 'range', 'updated_by', 'updated_date', 'status'
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
