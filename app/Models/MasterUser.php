<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MasterUser extends Model
{

    protected $table = 'user';

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
        'user_id', 'name', 'username', 'role_id', 'is_active', 'phone', 'gender', 'contact_email', 
        'employee_id', 'image', 'created_by', 'updated_by', 'created_date', 'updated_date', 'id_company', 'deleted'
    ];

        /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'token'
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
