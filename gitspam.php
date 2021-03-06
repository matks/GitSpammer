<?php

$autoloadFile = 'vendor/autoload.php';
if (file_exists($autoloadFile)) {
    define('AUTOLOAD_COMPOSER_INSTALL', $autoloadFile);
}

if (!defined('AUTOLOAD_COMPOSER_INSTALL')) {
    echo 'You need to set up the project dependencies using the following commands:' . PHP_EOL .
        'wget http://getcomposer.org/composer.phar' . PHP_EOL .
        'php composer.phar install' . PHP_EOL;
    die(1);
}

require AUTOLOAD_COMPOSER_INSTALL;

if (count($argv) !== 6) {
    echo 'php gitspam.php <username> <password> <repositoryOwner> <repositoryName> <pullRequestID>' . PHP_EOL;
    die(1);
}

Matks\GitSpam\ConsoleManager::main($argv[1], $argv[2], $argv[3], $argv[4], $argv[5]);
