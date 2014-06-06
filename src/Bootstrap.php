<?php

defined('ROOT') || define('ROOT', realpath(__DIR__.'/../'));
defined('LIB') || define('LIB', ROOT.'/src');
defined('APP') || define('APP', 'Midnight');

$loader = require_once ROOT.'/vendor/autoload.php';
$loader->set('Midnight', LIB);

