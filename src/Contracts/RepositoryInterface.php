<?php

namespace App\Contracts;
use App\Entity\Entity;

interface RepositoryInterface{

    public function find($id): ?object;

    public function findOneBy(string $string, $value): ?object;

    public function findBy(array $criteria);  //an array for multiple where

    public function findAll(): array;

    public function sql(string $query);  //raw query. We do not know what return type will be.

    public function create(Entity $entity): ?object;

    public function update(Entity $entity, array $conditions = []): ?object;

    public function delete(Entity $entity, array $conditions = []): void;
    //when we delete record will not be in databse. Delete always returns void
}