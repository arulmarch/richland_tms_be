<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TrafficMonitoring extends Model
{

    protected $table = 'tb_traffic_monitoring';

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
        'id', 'transport_order_id', 'point_id', 'tm_state', 'tm_status', 'arrival_eta', 'arrival_etatime', 'arrival_ata', 
        'arrival_atatime', 'spm_submit', 'spm_submittime', 'loading_start', 'loading_starttime', 'loading_finish', 'loading_finishtime',
        'documentation', 'documentationtime', 'departure_eta', 'departure_etatime', 'departure_ata', 'departure_atatime', 'notes',
        'arrival_note', 'arrival_image', 'arrival_latlng', 'loading_start_note', 'loading_start_image', 'loading_start_latlng',
        'loading_finish_note', 'loading_finish_image', 'loading_finish_latlng', 'id_driver', 'created_by', 'created_date', 'deleted', 'id_company'
    ];

}
