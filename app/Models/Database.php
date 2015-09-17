<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Database
 *
 * @package App\Models
 */
class Database extends Model
{
    /**
     * @var string
     */
    protected $table = 'database_data';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['hostname', 'username', 'password', 'database', 'url', 'type', 'notes'];

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
