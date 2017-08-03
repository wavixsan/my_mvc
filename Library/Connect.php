<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04.07.2017
 * Time: 18:48
 */

namespace Library;


class Connect
{
    private $pdo;

    public function __construct($container){
        $db = $container->get('config')->includeConfig('db',true);
        try{
            $this->pdo = new \PDO("mysql: host={$db->host}; dbname={$db->base}",$db->user,$db->pass);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        }catch (\PDOException $e){
            Error::fatal_error('Connect: Проверьте конфигурации базы данных');
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}