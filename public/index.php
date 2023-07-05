<?php

require(__DIR__ . '/../vendor/autoload.php');

use App\QueryBuilder;

$config = [
    'driver'   => 'mysql',
    'host'     => 'localhost',
    'dbname'   => 'api-service',
    'login'    => 'root',
    'password' => '',
];

$queryBuiler = new QueryBuilder($config);

$queryBuiler->select(['*'])
            ->from('users')
            ->where('age', '=', 19);

$users = $queryBuiler->execute();

dump($users);

$namesAndEmail = $queryBuiler->select(['name', 'email'])
                            ->from('users')
                            ->execute();

dump($namesAndEmail);

$userData =  [
   'name'     => 'Vlad',
   'email'    => 'vladgerasimuk@yandex.ru' . rand(0, 1000),
   'age'      => 19,
   'password' => md5('jsalj1laklsdj'),
];

$createUser = $queryBuiler->insert('users', $userData)
                          ->execute();

$updateUser = $queryBuiler->update('users')
                          ->set('name', 'vlad')
                          ->where('name', '=', 'vladislav')
                          ->execute();
                          
$deleteUser = $queryBuiler
                ->from('users')
                ->where('age', '=', 18)
                ->execute();
