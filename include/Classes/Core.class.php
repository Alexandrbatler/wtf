<?php

require_once 'Db.class.php';

class Core
{
    public $db = null;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function getErrors()
    {
        $errors = $this->db->getError();

        return $errors;
    }
}
