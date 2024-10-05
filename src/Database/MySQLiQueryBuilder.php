<?php
namespace App\Database;
use App\Exception\InvalidArgumentException;

class MySQLiQueryBuilder extends QueryBuilder{

    private $resultSet;
    private $result;
    const BIND_PARAM_INT = 'i';
    const BIND_PARAM_STRING = 's';
    const BIND_PARAM_DOUBLE = 'd';

    public function get(){
      
        if(!$this->resultSet){
            $this->resultSet = $this->statement->get_result();
            $this->restult = $this->resultSet->fetch_all(MYSQLI_ASSOC); 
        }

        return $this->result;
    }

    public function count(){
    
        //make sure if get all is not call, we get all result before return count
        if(!$this->resultSet){
            $this->get();
        }
       return $this->resultSet ? $this->resultSet->num_rows : false;
    }

    public function lastInsertId(){
        return $this->connection->insert_id;
    }

    public function prepare($query){
        return $this->connection->prepare($query);
    }

    public function statement($queryStatement){
     //To execute we have to invoke mysqli_stmt bind_param method and bind values before execute.  
        if(!$queryStatement){
            throw new InvalidArgumentException("Failed to prepare statement for mysqli bindings");
        }
 
        //check if there is bindings to parsebindings
       if($this->bindings){
           //parse bindings 
           $bindings = $this->parseBindings($this->bindings);

           $reflectionObj = new \ReflectionClass('mysqli_stmt');
           $method = $reflectionObj->getMethod('bind_param');
           $method->invokeArgs($queryStatement, $bindings);
       }

       $queryStatement->execute();
       $this->bindings = [];
       $this->placeholders = [];

       return $queryStatement;
    }

    private function parseBindings(array $theBindings){
        //statement->bind_param("si", $string, $int)

        $bindings = [];

        if(count($theBindings) === 0){
            return $this->bindings;
        }

        $bindingTypes = $this->parseBindingTypes();
        $bindings[] = & $bindingTypes;

        for($i = 0; $i < count($theBindings); $i++){
            $bindings[] = & $theBindings[$i];
        }
      
        return $bindings;
    }

    private function parseBindingTypes(){
        $bindingTypes = [];
         foreach($this->bindings as $type){
           if(is_int($type)){
               $bindingTypes[] = self::BIND_PARAM_INT;
           }

            if(is_string($type)){
                $bindingTypes[] = self::BIND_PARAM_STRING;
            }

            if(is_float($type)){
                $bindingTypes[] = self::BIND_PARAM_DOUBLE;
            }
         }

     return implode('', $bindingTypes);

    }

    public function fetchInto($className){
          $result = [];
          
          $this->resultSet = $this->statement->get_result();

          //just by doing $result = $this->resultSet->fetch_obj($classname) we only get 1 row  
          //so we loop thro all the rows
        while($obj = $this->resultSet->fetch_object($className)){
            $result[] = $obj;
        }

        return $this->result = $result;
    }

    public function beginTransaction(){
       $this->connection->begin_transaction();
    }

}


?>