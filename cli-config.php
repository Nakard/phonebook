<?php
/**
 * cli-config.php
 *
 * Creation date: 2014-04-24
 * Creation time: 18:21
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

require_once 'bootstrap.php';

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
        'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($entityManager->getConnection()),
        'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager)
    )
);