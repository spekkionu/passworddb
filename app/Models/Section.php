<?php

namespace App\Models;

use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    protected $guarded = [];

    public $sortable = [
        'order_column_name' => 'sort',
        'sort_when_creating' => true,
    ];

    /**
     * Get the post that owns the comment.
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the comments for the blog post.
     */
    public function data()
    {
        return $this->hasMany(Data::class)->ordered();
    }

    public function buildSortQuery()
    {
        return static::query()->where('site_id', $this->site_id);
    }
}
