<?php

// namespace App;
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ManifestFile extends Model
{

    protected $table = 'tb_manifest_files';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; // disable all behavior


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'manifest_id', 'file_name', 'uploaded_by', 'uploaded_date'
    ];

}
