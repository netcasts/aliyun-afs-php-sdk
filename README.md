# AliyunAFS
<p>阿里云人机验证服务SDK封装 for PHP</p>

# 系统要求
````
php >= 5.3
````

# 说明

- 基于 afs的sdk开发，没有对原sdk做任何修改
- 代码可以运行在任意项目中

# 安装
````
composer require timerlau/aliyun-afs-php-sdk
````

# 如果使用 packagist 源安装不了，请该用 github 源安装
```
{
    "repositories": {
        "packagist": {
            "type": "composer",
            "url": "https://packagist.phpcomposer.com"
        },
        "timerlau/aliyun-afs-php-sdk": {
            "type": "git",
            "url": "https://github.com/timerlau/aliyun-afs-php-sdk.git"
        }
    },
    "require": {
        "timerlau/aliyun-afs-php-sdk": "^1.0"
    }
}
```

# 使用
````
use Timerlau\AliyunAfs\Afs;

// 创建配置
$config = [];
$config['app_key'] = 'xxx';
$config['app_secret'] = 'xxxx';
$config['request_api'] = 'http://xxxx/response.jsonp';
// 可选配置
$config['selector_button_class'] = 'afs_button';
$config['selector_form_class'] = 'afs_form';
$config['selector_nvc_class'] = 'afs_nvc';
$config['selector_nvc_name'] = 'afs_nvc';

// 具体都有哪些配置，可以看config/Base.php

// demo 为 config 下必须存在的类, 里面的代码对应你在阿里云服务器上创建的配置里「系统集成代码」
$afs = new Afs('demo', $config);

// 获取html代码， 此处可以使用该方法获取，也可以直接在前端写，不强求
$html = $afs->html();

// 服务端验证 （基于 Laravel 实现的返回jsonp方式）
// $request->a 为前端获取到都 vnc ，并且是通过参数 a 传递过来的
$ret = [];
$ret['result']['code'] = $afs->validate($request->a);
return response()->jsonp($request->callback, $ret);

````
