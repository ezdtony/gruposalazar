<?php

class data_conn
{
    private $db_name = "u803991314_main";
    private $db_user = "u803991314_root";
    private $db_pass = "3Qn-J*TYN*qZ*!@";
    //private $db_host = "localhost";
    private $db_host = "195.35.39.84";

    private $db_conn;
    public function dbConn()
    {
        try {
            $this->db_conn = new PDO("mysql:host={$this->db_host}; dbname={$this->db_name}; charset=utf8", $this->db_user, $this->db_pass);
            $this->db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected";
        } catch (PDOException $e) {
            echo "ERROR" . $e->getMessage();
        }
        return $this->db_conn;
    }
}
