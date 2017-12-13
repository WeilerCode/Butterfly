<?php
/**
 * Created by Weiler.
 * User: Weiler
 * Email: weiler.china@gmail.com
 * Date: 2017/12/7
 * Time: 14:32
 */

return [
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => Weiler\Butterfly\Models\User::class
        ]
    ],
    //cache name
    'cache_name'    =>  [
        //后台分组列表
        'admin_group'   =>  'butterfly_admin_group',
        //后台目录列表html
        'admin_menu'    =>  'butterfly_admin_menu'
    ]
];
