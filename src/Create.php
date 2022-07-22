<?php

namespace Zisays\Rack;

class Create
{

    /**
     * 创建目录
     * @param $directoryName
     * @return void
     */
    public static function create($directoryName): void
    {
        $directoryPath = ROOT . DIRECTORY_SEPARATOR . $directoryName;
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath);
            chmod($directoryPath, 0777);
            self::createFile($directoryPath, $directoryName, 'Index', 'php');
            self::createFile($directoryPath, $directoryName, 'Config', 'php');
            self::createFile($directoryPath, $directoryName, $directoryName, 'env');
        }
    }

    /**
     * 创建文件
     * @param $directoryPath
     * @param $directoryName
     * @param $fileName
     * @param $fileType
     * @return void
     */
    public static function createFile($directoryPath, $directoryName, $fileName, $fileType): void
    {
        $file = $directoryPath . DIRECTORY_SEPARATOR . $fileName . '.' . $fileType;
        if (!is_file($file)) {
            $str = '';
            if ($fileName == 'Index') {
                $str = <<<EOF
<?php
namespace $directoryName\\$fileName;

class $fileName
{
    public function $fileName(): void
    {
        echo '欢迎使用Rack框架服务（Welcome to Rack Framework Services）';
    }
}
EOF;
            } elseif ($fileName == 'Config') {
                $str = <<<EOF
<?php
use Zisays\Rack\Env;

return [
    'database' => [
        'default' => 'mysql1',
        'mysql1' => [
            'dbms' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'dbname' => 'rack',
            'user' => 'root',
            'pwd' => 'root',
            'charset' => 'utf8',
            'prefix' => '',
            'pdoAttr' => array()
        ],
    ]
];
EOF;
            } elseif ($fileType == 'env') {

                $str = <<<EOF
;框架默认模块
default_model=home
;框架默认控制器
default_control=index
;框架默认方法
default_action=index
EOF;
            }
            $handle = fopen($file, "w+");
            fwrite($handle, $str);
            fclose($handle);
        }
    }

}