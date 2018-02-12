# butterfly
基于larave的后台管理系统composer包

## Install
1.安装laravel5.5  
`composer create-project --prefer-dist laravel/laravel app "5.5.*"`

2.加入git私有包发现到 `composer.json`
```json
    "repositories":[
        {
            "type": "git",
            "url": "https://gitee.com/weiler/butterfly.git"
        }
    ],
```
3.执行 `composer require weiler/butterfly dev-master`

4.创建数据库(utf-8)

5.设置`.env`配置，配置app.php配置中的默认语言

6.初始化Butterfly `php artisan butterfly:init`

7.(可选)如需使用本项目路由覆盖初始路由可以移除laravel自带的路由配置,或者加入 `app.php` 的 `providers` 配置中:  
```php
"providers" => [
    Weiler\Butterfly\Providers\ButterflyServiceProvider::class
]
```

8.开始Butterfly之旅吧! 默认后台管理地址 `域名/admin`