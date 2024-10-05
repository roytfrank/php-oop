<?php
namespace App\Entity;

abstract class Entity{

    abstract public function getId(): int;  //for updating

    abstract public function toArray(): array; //convert object to array at create and update

}

?>