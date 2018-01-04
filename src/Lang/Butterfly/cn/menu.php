<?php

return [
    /*
     |--------------------------------------------------------------------------
     | 后台目录
     |--------------------------------------------------------------------------
     */

    //后台首页
    'index'                     =>      [
        'index'                 =>      '首页'
    ],

    //我的面板
    'me'                        =>      [
        'index'                 =>      '我的面板',
        'upload'                =>      '上传头像(POST)',
        'edit'                  =>      '修改资料(POST)'
    ],

    //用户管理
    'member'                    =>      [
        'title'                 =>      '用户管理',
        'member'                =>      [
            'index'             =>      '会员管理',
            'add'               =>      '添加会员',
            'edit'              =>      '修改会员',
            'del'               =>      '删除会员',
            'upload'            =>      '上传头像'
        ],
        'memberGroup'           =>      [
            'index'             =>      '分组管理',
            'add'               =>      '添加分组',
            'edit'              =>      '修改分组',
            'del'               =>      '删除分组'
        ]
    ],


    //后台管理
    'manage'                    =>      [
        'title'                 =>      '后台管理',
        'menu'                  =>      [
            'index'             =>      '目录管理',
            'add'               =>      '添加目录',
            'add-post'          =>      '添加目录(POST)',
            'edit'              =>      '修改目录',
            'edit-post'         =>      '修改目录(POST)',
            'del'               =>      '删除目录',
            'display'           =>      '是否显示(POST)'
        ],
        'permissions'           =>      [
            'index'             =>      '权限管理',
            'add-group'         =>      '添加分组',
            'add-group-post'    =>      '添加分组(POST)',
            'edit-group'        =>      '修改分组',
            'edit-group-post'   =>      '修改分组(POST)',
            'del-group'         =>      '删除分组',
            'permissions'       =>      '权限设置',
            'permissions-post'  =>      '权限设置(POST)'
        ],
        'member'                =>      [
            'index'             =>      '后台用户',
            'add'               =>      '添加用户',
            'add-post'          =>      '添加用户(POST)',
            'edit'              =>      '修改用户',
            'edit-post'         =>      '修改用户(POST)',
            'del'               =>      '删除用户',
            'upload'            =>      '上传头像(POST)',
        ],
        'log'                   =>      [
            'index'             =>      '后台日志'
        ]
    ]
];
