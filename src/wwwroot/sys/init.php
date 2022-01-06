<?php
require __DIR__ . '/../vendor/autoload.php';
require 'lang.php';
error_reporting(0);

$mysqli = new mysqli($db_host, $db_user, $db_pass, "EatMan");

$mustache = new Mustache_Engine(array(
    // 'template_class_prefix' => '__tpl_',
    // 'cache' => dirname(__FILE__).'/tmp/cache/mustache',
    // 'cache_file_mode' => 0666,
    // 'cache_lambda_templates' => true,
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/../views'),
    'partials_loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/../views/partials'),
    'escape' => function($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    },
    'charset' => 'UTF-8',
    'logger' => new Mustache_Logger_StreamLogger('php://stderr'),
    'strict_callables' => true,
    'pragmas' => [Mustache_Engine::PRAGMA_FILTERS],
));