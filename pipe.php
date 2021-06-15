#!/usr/bin/php -q
<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('PPD_PATH', FCPATH.'ppd'.DIRECTORY_SEPARATOR);
$pathsPath = realpath(PPD_PATH . 'app/Config/Paths.php');
chdir(__DIR__);
require $pathsPath;
$paths = new Config\Paths();
$app = require rtrim($paths->systemDirectory, '/ ') . '/bootstrap.php';
helper(['cookie','form','html','helpdesk','number','filesystem','text']);
$mailFetcher = new \App\Controllers\MailFetcher();
$mailFetcher->pipe();