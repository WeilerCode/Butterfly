<?php

namespace Weiler\Butterfly\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminLog extends Model
{
    protected $table = 'butterfly_admin_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'uid', 'event', 'origin', 'ending', 'ip', 'iso_code', 'city', 'created_at'
    ];

    public $timestamps = false;
}
