<?php
namespace App;

class Model
{
    public $db;

    public function __construct($db_host, $db_name, $db_username = 'root', $db_password = '')
    {
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
        $opt = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ];
        try {
            $db = new \PDO($dsn, $db_username, $db_password, $opt);
            $this->db = $db;
        } catch (\PDOException $e) {
            App::set('error', 'Ошибка подключения к базе данных:');
        }
    }
}
