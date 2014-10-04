<?php

defined('ROOT') || define('ROOT', realpath(__DIR__.'/../'));
defined('LIB') || define('LIB', ROOT.'/src');
defined('APP') || define('APP', 'Midnight');

$is_ec2 = (is_dir('/home/ec2-user')) ? true: false;
//$is_ec2 = (is_dir('/home/ec2-user')) ? true: true;
defined('IS_EC2') || define('IS_EC2', $is_ec2);

$loader = require_once ROOT.'/vendor/autoload.php';
$loader->set('Midnight', LIB);

