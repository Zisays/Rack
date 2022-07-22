<?php

namespace Zisays\Rack;

class Route
{
    public static string $model; //模块
    public static string $control; //控制器
    public static string $action; //方法

    public static function run($project): void
    {
        $url_string = $_SERVER['REQUEST_URI'];
        if (isset($url_string) and $url_string !== '/') {
            $request_url = str_replace(array('.html', '.php', '.asp', '.htm', '.js', 'http://', 'https://', $_SERVER['SERVER_NAME']), '', $url_string);
            if (str_contains($request_url, '?')) {
                $str = explode('?', trim($request_url, '?'));
                $url = explode('/', substr($str[0], 1));
            } else {
                $url = explode('/', trim($request_url, '/'));
            }
            if (count($url) == 1 and !empty($url[0])) {
                self::$model = strtolower(ENV::get('DEFAULT_MODEL'));
                self::$control = strtolower(ENV::get(('DEFAULT_CONTROL')));
                self::$action = $url[0];
            } elseif (count($url) == 2 and !empty($url[1])) {
                self::$model = $url[0];
                self::$control = $url[1];
            }
            self::url($url);
        } else {
            self::url();
        }
        self::show();
    }

    public static function url($url = ''): void
    {
        if (empty($url)) {
            self::$model = ucfirst(ENV::get(('default_model')));
            self::$control = ucfirst(ENV::get(('default_control')));
            self::$action = ENV::get(('default_action'));
        } else {
            if (!empty($url[0])) {
                self::$model = ucfirst($url[0]);
            } else {
                self::$model = ucfirst(ENV::get(('default_model')));
            }

            if (!empty($url[1])) {
                self::$control = ucfirst($url[1]);
            } else {
                self::$control = ucfirst(ENV::get(('default_control')));
            }

            if (!empty($url[2])) {
                self::$action = $url[2];
            } else {
                self::$action = ENV::get(('default_action'));
            }
            if (count($url) > 2) {
                $i = 3;
                while ($i < count($url)) {
                    if (isset($url[$i + 1])) {
                        $_GET[$url[$i]] = $url[$i + 1];
                    }
                    $i = $i + 2;
                }
            }
        }
    }

    public static function show(): void
    {
        $file = self::$model . DIRECTORY_SEPARATOR . 'Control' . DIRECTORY_SEPARATOR . self::$control . 'Control.php';
        if (file_exists($file)) {
            $control = self::$model . '\Control\\' . self::$control . 'Control';
            $model = new $control();
            if (empty($model)) {
                echo '【' . self::$model . '】模块不存在!';
            } else {
                if (!method_exists($model, self::$action)) {
                    error::output('您访问的方法不存在：' . self::$action, __FILE__);
                } else {
                    $GLOBALS['twin']['model'] = strtolower(self::$model);
                    $GLOBALS['twin']['control'] = strtolower(self::$control);
                    $GLOBALS['twin']['action'] = strtolower(self::$action);
                    if (method_exists($model, 'init')) {
                        $model->init();
                    }
                    $action = self::$action;
                    $model->$action();
                }
            }
        } else {
            error::output('您访问的文件不存在：' . $file, __FILE__);
        }
    }


}
