# butterfly
基于laravel的后台管理系统composer包

## Install
1.安装laravel5.5  
`composer create-project --prefer-dist laravel/laravel app "5.5.*"`

2.执行 `composer require weiler/butterfly`

3.创建数据库  

4.设置`.env`配置，配置app.php配置中的默认语言

5.初始化Butterfly `php artisan butterfly:init`

6.(可选)如需使用本项目路由覆盖初始路由可以移除laravel自带的路由配置,或者加入 `app.php` 的 `providers` 配置中:  
```php
"providers" => [
    Weiler\Butterfly\Providers\ButterflyServiceProvider::class
]
```

7.开始Butterfly之旅吧! 默认后台管理地址 `域名/admin`