<?php
/**
 * 配置文件都 Base 类
 * 每个配置文件都要继承该类
 * 
 * @author songmw<songmingwei@100tal.com>
 * @since  18.08.22
 */
namespace Timerlau\AliyunAfs\Config;

class Base {

    // 支持的配置文件
    public $configTpl = [
        // 通过阿里云获取 appkey 和 appsecret
        'app_key', 'app_secret',
        // 前端校验的地址，绝对地址
        'request_api',
        // 前端点击触发校验的按钮类名
        'selector_button_class',
        // 前端提交数据的form表单类名
        'selector_form_class',
         // 前端提交 nvc 的 input表单类名
        'selector_nvc_class',
         // 前端提交 nvc 的 input表单名称
        'selector_nvc_name',
        // js的文件时间戳
        't',
    ];

    // 配置默认值
    public $t;
    public $selector_button_class = 'afs_button';
    public $selector_form_class = 'afs_form';
    public $selector_nvc_class = 'afs_nvc';
    public $selector_nvc_name = 'afs_nvc';

    public function __construct($config)
    {
        $this->t = date('ymdH');
        $this->config = $config;

        foreach ($this->config as $key => $val) {
            if (!in_array($key, $this->configTpl)) continue;
            $this->$key = $val;
        }
    }
}