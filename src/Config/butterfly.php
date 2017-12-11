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
    ]
];
