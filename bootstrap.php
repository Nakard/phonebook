<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(
    array(__DIR__.'/models/Entity/'), $isDevMode
);

$connection = array(
    'driver'    =>  'pdo_mysql',
    'host'      =>  'localhost',
    'user'      =>  'root',
    'password'  =>  'am021086',
    'port'      =>  '3306',
    'dbname'    =>  'ZendPhoneBook'
    //'path'      =>  __DIR__.'/phonebook.db.sqlite'
);

$entityManager = EntityManager::create($connection, $config);