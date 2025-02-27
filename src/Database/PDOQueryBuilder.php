<?phP
namespace App\Database;
use PDO;

class PDOQueryBuilder extends QueryBuilder{

    public function get(){
    return  $this->statement->fetchAll();
    }

    public function count(){
    return $this->statement->rowCount();
    }

    public function lastInsertId(){
    return $this->connection->lastInsertId();
    }

    public function prepare($query){
    return $this->connection->prepare($query);
    }

    public function statement($statement){
    $statement->execute($this->bindings);
    $this->bindings = [];
    $this->placeholders = [];
    return $statement;
    }

    public function fetchInto($className){
    return $this->statement->fetchAll(PDO::FETCH_CLASS, $className);
    }
    
    public function beginTransaction(){
        $this->connection->beginTransaction();
    }

}


?>