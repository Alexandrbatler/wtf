<?php

class Db
{
    private $pdo = null;
    private $host = '';
    private $login = '';
    private $password = '';
    private $db = '';
    private $dbms = '';
    private $dsn = '';

    private $errors = [];

    public function __construct()
    {
        $settings = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/include/settings.ini", true);

        $this->db = $settings['db'];
        $this->dbms = $settings['dbms'];
        $this->host = $settings['host'];
        $this->login = $settings['login'];
        $this->password = $settings['password'];
        $this->dsn = "{$this->dbms}:dbname={$this->db};host={$this->host}";
    }

    public function connect()
    {
        try {
            $this->pdo = new PDO($this->dsn, $this->login, $this->password);
        } catch (PDOException $e) {
            $this->errors[] = $e->getMessage();

            return false;
        }

        return true;
    }

    public function close()
    {
        $this->pdo = null;
    }

    public function query($sql, $data)
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
        } catch(PDOException $e) {
            $this->errors[] = $e->getMessage();

            return false;
        }

        return $stmt;
    }

    public function qfa($sql, $data = [], $fetchStyle = PDO::FETCH_NUM)
    {
        if ($stmt = $this->query($sql, $data)) {
            return $stmt->fetchAll($fetchStyle);
        }

        return false;
    }

    public function getErrors()
    {
        if ($this->pdo !== null) {
            $errArr = $this->pdo->errorInfo();
            if (isset($errArr)) {
                $this->errors[] = $errArr[2];
            }
        }

        return $this->errors;
    }
}
