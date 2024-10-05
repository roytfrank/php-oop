<?php
namespace App\Database;
use App\Contracts\DatabaseConnectionInterface;
use App\Database\AbstractConnection;
use PDO, PDOException;
class PDOConnection extends AbstractConnection implements DatabaseConnectionInterface{

    const REQUIRED_CONFIG_KEYS = ["driver", "host", "username", "password", "dbname", "default_fetch"];


    public function connect(): PDOConnection{

        $credentials = $this->parseCredentials($this->credentials);
    try{
         $this->connection = new PDO(...$credentials);
         $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $this->credentials['default_fetch']);
    }catch(PDOException $ex){
        throw new DatabaseConnectionException($ex->getMessage(), $this->credentials, 500);
    }
        return $this;
    }

    public function getConnection(){
      return $this->connection;
    }

    public function parseCredentials(array $credentials): array{
        $dsn = $credentials['driver'].":host=".$credentials['host'].";dbname=".$credentials['dbname'];
        return [$dsn, $credentials['username'], $credentials['password']];
    }

}

?>