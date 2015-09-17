<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class FTP
 *
 * @package App\Models
 */
class FTP extends Model
{
    /**
     * @var string
     */
    protected $table = 'ftp_data';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['username', 'password', 'path', 'hostname', 'type', 'notes'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'website_id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function website()
    {
        return $this->belongsTo('App\Models\Website');
    }
}
