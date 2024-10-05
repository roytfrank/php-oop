<?php 

namespace App\Database;
use App\Exception\InvalidArgumentException;

trait GetQuery{

    public function getQuery(string $type){

        //use a switch statement to perform query based on type.
        switch($type){

        case self::DML_TYPE_SELECT:
        return   sprintf("SELECT %s FROM %s WHERE %s",
                     $this->fields, $this->table, implode(' and ', $this->placeholders)  
        );
    break;
        case self::DML_TYPE_INSERT:
        return   sprintf("INSERT INTO %s(%s)VALUES(%s)",
                    $this->table, implode(', ', $this->fields), implode(', ', $this->placeholders)  
          );
    break;
        case self::DML_TYPE_UPDATE:
        return    sprintf("UPDATE %s SET %s WHERE %s",
                    $this->table, implode(', ', $this->fields), implode(', ', $this->placeholders)  
        );
    break;
        case self::DML_TYPE_DELETE:
        return     sprintf("DELETE FROM %s WHERE %s",
                    $this->table, implode(', ', $this->placeholders)  
        );
    break;
        default:
        throw new InvalidArgumentException(sprintf("The dml type %s is not supported", $type));

        }
    }
}







?>