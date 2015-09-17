<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class ControlPanel
 *
 * @package App\Models
 */
class ControlPanel extends Model
{
    /**
     * @var string
     */
    protected $table = 'control_panels';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['username', 'password', 'url', 'notes'];

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
