<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));

$host = gethostname();
$baseUri = '/';

//var_dump($host); die;

    $connection = array(
        'adapter'     => 'Mysql',
        'host' => '127.0.0.1',
		'username' => 'root',
		'password' => 'root',
		'dbname' => 'scorepesa',
        'charset'     => 'utf8',
        'unix_socket'   => '/Applications/MAMP/tmp/mysql/mysql.sock',
    );
    $baseUri = '/mobile/';


return new \Phalcon\Config(array(
    'database' => $connection,
    'application' => array(
        'controllersDir' => APP_PATH . '/app/controllers/',
        'modelsDir'      => APP_PATH . '/app/models/',
        'migrationsDir'  => APP_PATH . '/app/migrations/',
        'viewsDir'       => APP_PATH . '/app/views/',
        'layoutsDir'       => APP_PATH . '/app/views/layouts',
        'pluginsDir'     => APP_PATH . '/app/plugins/',
        'libraryDir'     => APP_PATH . '/app/library/',
        'vendorDir'       => APP_PATH . '/vendor/',
        'cacheDir'       => APP_PATH . '/app/cache/',
        'baseUri'        => $baseUri,
    )
));
