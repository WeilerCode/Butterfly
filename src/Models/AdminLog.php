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
        'type', 'uid', 'event', 'origin', 'ending', 'ip', 'iso_code', 'city'
    ];

    /**
     * 返回carbon改为返回Unix时间戳
     * @return time
     */
    public function freshTimestamp()
    {
        return time();
    }
    public function fromDateTime($value)
    {
        return $value;
    }
    protected function asDateTime($value) {
        return $value;
    }
}
