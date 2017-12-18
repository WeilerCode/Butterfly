<?php
/**
 * Created by Weiler.
 * User: Weiler
 * Email: weiler.china@gmail.com
 * Date: 2017/12/7
 * Time: 14:32
 */

return [
    // route name
    'route_name'    =>  [
        'admin'     =>  'admin',
        'img'       =>  'img'
    ],
    // auth
    'providers'     => [
        'users'     => [
            'driver'=> 'eloquent',
            'model' => Weiler\Butterfly\Models\User::class
        ]
    ],
    //cache name
    'cache_name'    =>  [
        //后台分组列表
        'admin_group'   =>  'butterfly_admin_group',
        //后台目录列表html
        'admin_menu'    =>  'butterfly_admin_menu'
    ],
    // Upload
    'upload'        =>  [
        'member_path'                =>  'uploads/member/',
        'update_path'                =>  'uploads/images/',
        'member_default'             =>  'member.png',
        'picture_default'            =>  ''
    ]
];
