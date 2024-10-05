<?php
namespace App\Helpers;
use App\Database\PDOConnection;
use App\Database\MySQLiConnection;
use App\Database\PDOQueryBuilder;
use App\Database\MySQLiQueryBuilder;
use App\Exception\DatabaseConnectionException;
use App\Helpers\Config;
use App\Database\QueryBuilder;

class QueryBuilderFactory{

    public static function make(string $credentialFile = "database", string $credentialType = "pdo", array $options = []): QueryBuilder{

        $connection = null;
        $credentials = array_merge(Config::get($credentialFile, $credentialType), $options);

        switch($credentialType){
            case 'pdo':
                 $connection = (new PDOConnection($credentials))->connect();
                 return new PDOQueryBuilder($connection);
            break;
            case 'mysqli':
                $connection = (new MySQLiConnection($credentials))->connect();
                return new MySQLiQueryBuilder($connection);
            break;
            default:
            throw new DatabaseConnectionException("The connection type is not supported", ['type' => $credentialType]);
        }
    }
}


?>