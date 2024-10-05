<?php

namespace App\Database;
use App\Contracts\DatabaseConnectionInterface;

abstract class QueryBuilder{

    protected $connection;
    protected $table;
    protected $statement;
    protected $operation = self::DML_TYPE_SELECT;
    protected $bindings;
    protected $placeholders;
    protected $fields;

    const OPERATORS = ['=', '>=', '<=', '<', '>', '<>'];
    const COLUMNS = '*';
    const PLACEHOLDER = '?';
    const DML_TYPE_SELECT = 'SELECT';
    const DML_TYPE_INSERT = 'INSERT';
    const DML_TYPE_UPDATE = 'UPDATE';
    const DML_TYPE_DELETE= 'DELETE';
    public $query;

    use GetQuery;

    public function __construct(DatabaseConnectionInterface $connectiontype){
           $this->connection = $connectiontype->getConnection();
    }

    public function table(string $table){
        $this->table = $table;
        return $this;
    }

    public function where(string $column, $operator = self::OPERATORS[0], $value = null){
          //check if an operator is passed 
        if(!in_array($operator, self::OPERATORS) && $value === null){
             $value = $operator; 
             $operator = self::OPERATORS[0];
        }

       $this->parseWhere([$column => $value], $operator);
       $queryStatement = $this->prepare($this->getQuery($this->operation));
       $this->statement = $this->statement($queryStatement);  //get statement to execute
       return $this;
    }

    private function parseWhere($columnsValues, $operator){
         //separate the columns and value for binding.
         foreach($columnsValues as $column => $value){
             $this->placeholders[] = sprintf('%s %s %s', $column, $operator, self::PLACEHOLDER);
             $this->bindings[] = $value;
         }    
      return $this;
    }

    public function select(string $fields = self::COLUMNS){
        $this->operation = self::DML_TYPE_SELECT;
        $this->fields = $fields;
        return $this;
    }

//the same methods for pdo and mysqli connection
    public function create(array $data){

      foreach($data as $column => $value){
          $this->placeholders[] = self::PLACEHOLDER;
          $this->bindings[] = $value;
          $this->fields[] = $column;
      }

       $queryStatement = $this->prepare($this->getQuery(self::DML_TYPE_INSERT));
       $this->statement = $this->statement($queryStatement);

     return (int) $this->lastInsertId();
    }

    public function update(array $data){
        $this->fields = [];
        $this->operation = self::DML_TYPE_UPDATE;
        foreach($data as $column => $value){
            $this->fields[] = sprintf("%s %s %s", $column, self::OPERATORS[0], "'$value'");
        }
      return $this;
    }

    public function delete(array $data){
         $this->operation = self::DML_TYPE_DELETE;
         return $this;
    }

    public function raw($query){
        $queryStatement = $this->prepare($query);
        $this->statement = $this->statement($queryStatement);
        return $this;
    }

    public function find($id){
        return $this->select()->where('id', $id)->first();
    }

    public function findOneBy($field, $value){
        return $this->select()->where($field, $value)->first();
    }

    public function first(){
         return $this->count() ? $this->get()[0] : null;
    }

    public function rollback(): void{
       $this->connection->rollback();
    }

//abstrcat methods differ between databases
    abstract public function get();

    abstract public function count();

    abstract public function lastInsertId();

    abstract public function prepare($query);

    abstract public function statement($queryStatement);

    abstract public function fetchInto($className);

    abstract public function beginTransaction();

}











?>