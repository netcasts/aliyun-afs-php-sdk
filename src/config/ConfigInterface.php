<?php
/**
 * 主要配置信息
 * 
 * @author songmw<songmingwei@100tal.com>
 * @since  18.08.22
 */
namespace Timerlau\AliyunAfs\Config;

interface ConfigInterface {

    /**
     * 前端的系统集成代码
     */
    public function frontend();

    /**
     * 后端的系统集成代码，用来校验
     * 
     * @param string nvc
     */
    public function validate($nvc);

}