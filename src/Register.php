<?php

namespace Zisays\Rack;

class Register
{
    public static function constant(): void
    {
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }

    public static function error(): void
    {
        //注册一个会在php中止时执行的函数
        register_shutdown_function('Zisays\Rack\Error::error_end');
        //注册用户自定义错误处理方法
        set_error_handler("Zisays\Rack\Error::error_handler");
    }
}