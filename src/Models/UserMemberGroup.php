<?php

namespace Weiler\Butterfly\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMemberGroup extends Model
{
    use SoftDeletes;

    protected $table = 'butterfly_member_group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lv', 'color', 'permissions'
    ];

    protected $dates = ['deleted_at'];
}
