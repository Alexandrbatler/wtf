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

    private $errorArr = null;

    public function __construct()
    {
        $settings = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/include/settings.ini", true);

        $this->db = $settings['db'];
        $this->dbms = $settings['dbms'];
        $this->host = $settings['host'];
        $this->login = $settings['login'];
        $this->password = $settings['password'];
        $this->dsn = "{$this->dbms}:dbname={$this->db};host={$this->host}";

        $this->errorArr = [];
    }

    public function connect()
    {
        try {
            $this->pdo = new PDO($this->dsn, $this->login, $this->password);
        } catch (PDOException $e) {
            $this->errorArr[] = $e->getMessage();

            return false;
        }

        return true;
    }

    public function close()
    {
        $this->pdo = null;
    }

    public function query($sql)
    {
        return $this->pdo->query($sql);
    }

    public function qfa($sql, $fetchStyle = PDO::FETCH_NUM)
    {
        if ($stmt = $this->query($sql)) {
            return $stmt->fetchAll($fetchStyle);
        }

        return false;
    }

    public function getError()
    {
        if ($this->pdo !== null) {
            $errArr = $this->pdo->errorInfo();
            if (isset($errArr)) {
                $this->errorArr[] = $errArr[2];
            }
        }

        return $this->errorArr;
    }
}
