<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    //articles table in database
    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo('App\Models\User','author_id');
    }
    public function contributors()
    {
        return $this->belongsToMany('App\Models\User','article_contributor', 'contributor_id', 'article_id')->withTimestamps();
    }
}
