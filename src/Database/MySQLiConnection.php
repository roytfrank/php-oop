<?php

namespace App\Database;
use App\Database\AbstractConnection;
use App\Contracts\DatabaseConnectionInterface;
use mysqli, mysqli_driver;

class MySQLiConnection extends AbstractConnection implements DatabaseConnectionInterface{
  
    const REQUIRED_CONFIG_KEYS = ['host', 'username', 'password', 'dbname', 'default_fetch'];

    public function connect(): MySQLiConnection{

        $driver = new mysqli_driver;
        $driver->report_mode = MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR;
        $credentials = $this->parseCredentials($this->credentials);
        
        try{
        $this->connection = new mysqli(...$credentials);
        }catch(\Throwable $ex){
            throw new DatabaseConnectionException($ex->getMessage(), $this->credentials, 500);
        }
        return $this;

    }

    public function getConnection(){
        return $this->connection;
    } 

    protected function parseCredentials(array $credentials): array{
        return [$credentials['host'], $credentials['username'], $credentials['password'], $credentials['dbname']];
    }

}


?>