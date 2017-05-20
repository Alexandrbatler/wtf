<?php

require_once 'Db.class.php';
require_once 'Page.class.php';

class Core
{
    public $db = null;
    public $page = null;

    public function __construct()
    {
        $this->db = new Db();
        $this->page = new Page();
    }

    public function getErrors()
    {
        $errors = $this->db->getErrors();

        return $errors;
    }
}
