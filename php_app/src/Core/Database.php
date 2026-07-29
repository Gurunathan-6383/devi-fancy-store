<?php
namespace App\Core;

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
        $this->pdo = new \PDO($dsn, $config['user'], $config['password'], [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch($sql, $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }

    public function fetchAll($sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function insert($sql, $params = [])
    {
        $this->query($sql, $params);
        return $this->pdo->lastInsertId();
    }

    public function update($sql, $params = [])
    {
        return $this->query($sql, $params)->rowCount();
    }

    public function delete($sql, $params = [])
    {
        return $this->query($sql, $params)->rowCount();
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}
