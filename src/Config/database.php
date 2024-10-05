<?php

return [

     "pdo" => [
         "driver" => "mysql",
         "host" => "localhost",
         "dbname" => "bug_app",
         "username" => "root",
         "password" => "",
         "default_fetch" => PDO::FETCH_OBJ
     ],

     "mysqli" => [
        "host" => "localhost",
        "username" => "root",
        "password" => "",
        "dbname" => "bug_app",
        "default_fetch" => PDO::FETCH_OBJ
    ]

]

?>