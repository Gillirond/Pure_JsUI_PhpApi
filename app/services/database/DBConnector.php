<?php

//DB connect and query functional
class DBConnector
{
    //mysql db configs
    private $configs = [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'for_dex_digital',
        'username' => 'root',
        'password' => ''
    ];

    public function __construct()
    {
        $this->conn = $this->openConnection();
    }

    public function openConnection()
    {
        $this->conn = new mysqli($this->configs['host'], $this->configs['username'], $this->configs['password'], $this->configs['database']) or die("Connection failed: %s\n".$this->conn->error);
        return $this->conn;
    }

    public function query($queryString)
    {
        return $this->conn->query($queryString);
    }

    //Closes connection when class is being destructed
    public function __destruct()
    {
        if(isset($this->conn)) {
            $this->conn->close();
        }
    }
}