<?php

namespace Zisays\Rack;


class Rack
{
    /**
     * 初始化
     * @param $debug
     * @return void
     */
    public static function init($debug): void
    {
        self::debug($debug);
        Register::constant();
        Register::error();
    }

    /**
     * 调试模式
     * @param $debug
     * @return void
     */
    public static function debug($debug): void
    {
        if ($debug) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
        }
    }

    /**
     * 创建工程
     * @param $project
     * @return void
     */
    public static function create($project): void
    {
        Create::create($project);
    }

    /**
     * 运行工程
     * @param $project
     * @return void
     */
    public static function run($project): void
    {
        Env::load(ROOT . '/' . $project . '/' . $project . '.env');
        Route::run($project);
    }
}