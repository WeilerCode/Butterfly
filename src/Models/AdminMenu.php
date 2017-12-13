<?php

namespace Weiler\Butterfly\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminMenu extends Model
{
    use SoftDeletes;

    protected $table = 'butterfly_admin_menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'parentID', 'routeName', 'listOrder', 'display', 'icon'
    ];

    protected $dates = ['deleted_at'];
}
