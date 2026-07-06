<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CloudWatchSearchHistory extends Model
{
    protected $table = 'cloudwatch_search_histories';
    protected $fillable = ['search_query'];
}