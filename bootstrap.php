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
    'user'      =>  'phonebook',
    'password'  =>  'phonebook',
    'port'      =>  '3306',
    'dbname'    =>  'ZendPhoneBook'
);

$entityManager = EntityManager::create($connection, $config);