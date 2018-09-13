<?php
/**
 * afs主要方法
 *
 * @author  songmw<imphp@qq.com>
 * @since   2018.08.22
 */
namespace Timerlau\AliyunAfs;
use afs\Core\Config;
use Timerlau\AliyunAfs\Config\ConfigInterface;

class Afs
{
    public $className;      // 类名
    public $classInstance;  // 对象
    public $config = [];

    public function __construct($class_name, $config = [])
    {
        Config::autoloader();
        $this->className = self::filter($class_name);
        $this->config = $config;

        $class = new \ReflectionClass($this->className);
        $this->classInstance = $class->newInstance($this->config);
    }

    /** 
     * 获取前台方法
     */
    public function html()
    {
        return Launch::frontend($this->classInstance);
    }
    
    /**
     * 服务端校验
     * 
     * @param string $nvc 由前端传递过来
     */
    public function validate($nvc = '')
    {
        return Launch::validate($this->classInstance, $nvc);
    }

    /**
     * 判断类是否存在
     */
    public static function filter($config_name)
    {
        if (!$config_name) {
            throw new \Exception('Class Is Empty!', 1);
        }

        $config_name = 'Timerlau\\AliyunAfs\\Config\\' . ucfirst(strtolower($config_name));
        if (!class_exists($config_name)) {
            throw new \Exception(sprintf("class %s not found!", $config_name), 1);
        }
        return $config_name;
    }
}

class Launch
{
    /**
     * 获取前端代码
     */
    public static function frontend(ConfigInterface $config)
    {
        return $config->frontend();
    }

    /**
     * 后端验证
     */
    public static function validate(ConfigInterface $config, $nvc)
    {
        return $config->validate($nvc);
    }
}