<?php

namespace Zisays\Rack;


class Error
{
    /**
     * 设置用户自定义的错误处理
     * @param int $error_level 错误等级
     * @param string $error_message 错误消息
     * @param string $error_file 错误文件
     * @param int $error_line 错误行号
     */
    public static function error_handler(int $error_level, string $error_message, string $error_file, int $error_line): void
    {
        self::output($error_message, $error_file, $error_line, $error_level, '系统错误');
    }

    /** 设置用户自定义的异常错误
     * @param object $e 异常对象
     * @param string $Sql 异常sql
     */
    public static function error_try(object $e, string $Sql = ''): void
    {
        self::output($e->getMessage(), $e->getTrace()[1]['file'], $e->getTrace()[1]['line'], $e->getCode(), '异常错误', $Sql);
    }

    /**
     * 设置用户自定义运行结束时的错误
     * @return void
     */
    public static function error_end(): void
    {
        $e = error_get_last();
        $errType = match (error_get_last()) {
            1 => '致命的运行时错误',
            2 => '运行时警告 (非致命错误)',
            4 => '编译时语法解析错误',
            8, 8192 => '运行时通知',
            16 => '在 PHP 初始化启动过程中发生的致命错误',
            32 => 'PHP 初始化启动过程中发生的警告 (非致命错误)',
            64 => '致命编译时错误',
            128 => '编译时警告 (非致命错误)',
            256 => '用户产生的错误信息',
            512, 16384 => '用户产生的警告信息',
            1024 => '用户产生的通知信息',
            2048 => '启用 PHP 对代码的修改建议，以确保代码具有最佳的互操作性和向前兼容性。',
            4096 => '可被捕捉的致命错误',
            default => false
        };
        if ($errType !== false) {
            self::output($e['message'], $e['file'], $e['line'], $e['type'], '运行结束错误', '', $errType);
        }
    }

    /**
     * 输出错误样式
     * @param string $errStr
     * @param string $errFile
     * @param int $errLine
     * @param string $errNo
     * @param string $errTitle
     * @param string $errSql
     * @param string $errType
     */
    public static function output(string $errStr, string $errFile = '', int $errLine = 0, string $errNo = '0', string $errTitle = '系统错误', string $errSql = '', string $errType = ''): void
    {

        ?>
        <style>
            body {
                background: #2b2b2b;
                color: #CB742D;

            }

            .rhythm-frame-table {
                border-collapse: collapse;
                width: 100%;
                box-sizing: border-box;
            }

            .rhythm-frame-table tr th {
                border: solid 2px #323232;
                height: 20px;
                width: 100%;
                background: #232525;
                padding: 20px;
                font-size: 20px;
            }

            .rhythm-frame-table tr td {
                border: solid 2px #323232;
                height: 20px;
                text-align: left;
                background: #232525;
                padding: 10px;
            }
        </style>
        <table class="rhythm-frame-table">
            <thead>
            <tr>
                <th colspan="2"><?php echo $errTitle; ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            echo "<tr><td style='width: 10%;'>错误编号</td><td>" . $errNo . "</td></tr>";
            echo "<tr><td style='width: 10%;'>错误位置</td><td>" . $errFile . " ( " . $errLine . " 行 )" . "</td></tr>";
            echo "<tr><td style='width: 10%;'>错误信息</td><td>" . $errStr . "</td></tr>";
            if (!empty($errSql)) {
                echo "<b>【" . $errTitle . "】错误SQL</b>" . $errSql . "<br/>";
            }
            ?>
            </tbody>
        </table><br/>
        <?php
    }
}