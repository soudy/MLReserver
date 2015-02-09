<?php

class Model
{
    protected $db;

    /*
     * Not using a singleton for database
     * @see http://stackoverflow.com/a/4596323
     */
    public function __construct()
    {
        try {
            $this->db = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME .
                                ';charset=' . DB_CHARSET, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            die("Failed to open PDO database connection: $e");
        }
    }
}
