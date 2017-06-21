<?php
/**
 * @file     DB.class.php
 * @brief	 数据库初始化类
 * @details  数据库的配置+连接+关闭
 * @author   fengyuzhuo
 * @date     2015-5-25
 */
class DB
{
    const DB_DSN         = 'mysql:dbname=msg_record;host=localhost';
    const DB_USER        = 'root';
    const DB_PASSWORD    = '123';
    private static $_con = null;

    public function __construct()
    {
        self::$_con = self::init();
    }

    public function __destruct()
    {
        @self::$_con->close();
    }

    public static function init()
    {
        $option = [
                'charset' => 'utf8',
                ];
        $con =  new PDO(self::DB_DSN, self::DB_USER, self::DB_PASSWORD, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES'utf8';"]);

        return $con;
    }

    public function get()
    {
        return self::$_con;
    }
}
