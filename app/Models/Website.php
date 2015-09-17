<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Website
 *
 * @package App\Models
 */
class Website extends Model
{
    /**
     * @var string
     */
    protected $table = 'websites';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['name', 'domain', 'url', 'notes'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function admin()
    {
        return $this->hasMany('App\Models\Admin', 'website_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function controlpanel()
    {
        return $this->hasMany('App\Models\ControlPanel', 'website_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function database()
    {
        return $this->hasMany('App\Models\Database', 'website_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ftp()
    {
        return $this->hasMany('App\Models\FTP', 'website_id', 'id');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search = null)
    {
        if (empty($search)) {
            return $query;
        }
        $search = "%" . str_replace("%", "\\%", $search) . "%";

        return $query->where(function ($query) use ($search) {
            $query->where('name', 'LIKE', $search);
            $query->orWhere('domain', 'LIKE', $search);
            $query->orWhere('url', 'LIKE', $search);
        });
    }
}
