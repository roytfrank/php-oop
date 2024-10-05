<?php
 namespace App\Database;
 use App\Exception\MissingArgumentException;

 abstract class AbstractConnection{
    protected $credentials;
    protected $connection;

    const REQUIRED_CONFIG_KEYS = [];

    public function __construct($credentials){
        $this->credentials = $credentials;

        if(!$this->matchConfigKeyToCredentialKeys($this->credentials)){
            throw new MissingArgumentException(
                sprintf("The connection keys are invalid. Valid keys: %s",
                implode(", ", static::REQUIRED_CONFIG_KEYS))
            );
        }
    }

    private function matchConfigKeyToCredentialKeys(array $credentialKeys): bool{
        $matches = array_intersect(static::REQUIRED_CONFIG_KEYS, array_keys($credentialKeys));
        return count($matches) === count(static::REQUIRED_CONFIG_KEYS);
    }
     
    abstract protected function parseCredentials(array $array): array;

 }



?>