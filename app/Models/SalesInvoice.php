<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{

    protected $table = 'tb_sales_invoice';

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
        'id', 'reference', 'invoice_date', 'due_date', 'payment_date', 'type', 'from_date', 'to_date',
        'client_id', 'area_type', 'sub_total', 'total_vat', 'total_amount', 'inv_status', 'taxable', 
        'vat', 'created_by', 'updated_by', 'created_date', 'updated_date', 'deleted', 'id_company', 'payment_term',
        'file_name', 'sub_total_variable_cost', 'sub_total_cost_component'
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
