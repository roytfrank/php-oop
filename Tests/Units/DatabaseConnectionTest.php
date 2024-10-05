<?php
namespace Tests\Units;

use App\Database\PDOConnection;
use App\Database\MySQLiConnection;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;
use App\Contracts\DatabaseConnectionInterface;
use App\Exception\MissingargumentException;


class DatabaseConnectionTest extends TestCase{

    public function testCredentialsMissingArgumentException(){
        self::expectException(MissingArgumentException::class);
        $credentials = [];
        $pdo = new PDOConnection($credentials);
    }

    public function testItCanConnectToDatabaseWithPdo(){

        $credentials = $this->getCredentials("pdo");
        $pdo = (new PDOConnection($credentials))->connect();
        self::assertInstanceOf(DatabaseConnectionInterface::class, $pdo);

        return $pdo;
    }
  
    /** @depends testItCanConnectToDatabaseWithPdo */
    public function testItIsAValidPdoConnection(DatabaseConnectionInterface $pdo){
        self::assertInstanceOf(\PDO::class, $pdo->getConnection());
    }

    public function testMissingArgumentExceptionForMysqli(){
          self::expectException(MissingArgumentException::class);
          $credentials = [];
        $dbc = new MySQLiConnection($credentials);
    }

    public function testItCanConnectToDatabaseWithMysqli(){
        $credentials = $this->getCredentials('mysqli');
        $dbc = (new MySQLiConnection($credentials))->connect();
        self::assertInstanceOf(DatabaseConnectionInterface::class, $dbc);
        return $dbc;
    }

    /** @depends testItCanConnectToDatabaseWithMysqli */
    public function testItIsAValidMysqliConnection(DatabaseConnectionInterface $dbc){
         self::assertInstanceOf(\mysqli::class, $dbc->getConnection());
    }

    private function getCredentials(string $type){
         return array_merge(Config::get("database", $type),
                            ['dbname' => 'bug_app_testing']);
    }
}


?>