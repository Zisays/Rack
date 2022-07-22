<?php

require __DIR__ . '/vendor/autoload.php';

use Zisays\Rack\Rack;

//初始化
Rack::init(true);

//创建项目
Rack::create('home');

//运行项目
Rack::run('home');