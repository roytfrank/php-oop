<?php
//we can have an array as where we save our data. In that case we can swap the Query builder class for that
//otherwise we cab just use a factory to get the query builder.
namespace App\Repository;
use App\Contracts\RepositoryInterface;
use App\Entity\Entity;
use App\Contracts\ReposittoryInterface;
use App\Database\QueryBuilder;

abstract class Repository implements RepositoryInterface{

    protected static $table;
    protected static $className;

    /** @var QueryBuilder $queryBuilder **/
    private $queryBuilder;


    public function __construct(QueryBuilder $queryBuilder){
        $this->queryBuilder = $queryBuilder;
    }

    public function find($id): ?object{
    return $this->findOneBy('id', $id);
    }

    public function findOneBy(string $field, $value): ?object{
        $result = $this->queryBuilder->table(static::$table)
                            ->select()->where($field, $value)
                            ->fetchInto(static::$className);
        return ($result) ? $result[0] : null;
    }

    public function findBy(array $criteria){
        $this->queryBuilder->table(static::$table)->select();
        foreach($criteria as $criterion){
            $this->queryBuilder->where(...$criterion); //[["email", "=", "email@mail.com"], ["link", "=", "link"]]
        }

       return $this->queryBuilder->fetchInto(static::$className); //an object array is return. 1 object will be $result[0]

    }  //an array for multiple where

    public function findAll(): array{
         return $this->queryBuilder->raw("SELECT * FROM reports")->fetchInto(static::$className);
    }

    public function sql(string $query){
       return $this->queryBuilder->raw($query)->fetchInto(static::$className);
    }  //raw query. We do not know what return type will be.

    public function create(Entity $entity): ?object{
         $id = $this->queryBuilder->table(static::$table)->create($entity->toArray());
         return $this->find($id); //this will return an object
    }

    public function update(Entity $entity, array $conditions = []): ?object{
        $this->queryBuilder->table(static::$table)->update($entity->toArray());
        foreach($conditions as $condition){
         $this->queryBuilder->where(...$condition);
        }
    
        $this->queryBuilder->where('id', $entity->getId());
        return $this->find($entity->getId());

    }

    public function delete(Entity $entity, array $conditions = []): void{

        $this->queryBuilder->table(static::$table)->delete($entity->toArray());
        foreach($conditions as $condition){
         $this->queryBuilder->where(...$condition);
        }

        $this->queryBuilder->where('id', $entity->getId());
     //   $this->find($entity->getId());
    }

}








?>