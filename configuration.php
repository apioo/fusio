<?php

return array(

	// Available action classes. The array can be extended to provide custom 
	// implementations. Note the class must be resolvable by composer
	'fusio_action'            => [
		'Fusio\Action\BeanstalkPush',
		'Fusio\Action\CacheResponse',
		'Fusio\Action\Composite',
		'Fusio\Action\Condition',
		'Fusio\Action\HttpRequest',
		'Fusio\Action\Pipe',
		'Fusio\Action\RabbitMqPush',
		'Fusio\Action\SqlExecute',
		'Fusio\Action\SqlFetchAll',
		'Fusio\Action\SqlFetchRow',
		'Fusio\Action\StaticResponse',
	],

	// Available connection classes. The array can be extended to provide custom 
	// implementations. Note the class must be resolvable by composer
	'fusio_connection'        => [
		'Fusio\Connection\Beanstalk',
		'Fusio\Connection\DBAL',
		'Fusio\Connection\DBALAdvanced',
		'Fusio\Connection\MongoDB',
		'Fusio\Connection\Native',
		'Fusio\Connection\RabbitMQ',
	],

	// The url to the psx public folder (i.e. http://127.0.0.1/psx/public or 
	// http://localhost.com)
	'psx_url'                 => 'http://127.0.0.1/projects/fusio/public',

	// The input path 'index.php/' or '' if you use mod_rewrite
	'psx_dispatch'            => 'index.php/',

	// The default timezone
	'psx_timezone'            => 'UTC',

	// Whether PSX runs in debug mode or not. If not error reporting is set to 0
	'psx_debug'               => true,

	// Your SQL connections
	'psx_sql_host'            => 'localhost',
	'psx_sql_user'            => 'root',
	'psx_sql_pw'              => '',
	'psx_sql_db'              => 'fusio',

	// Path to the routing file
	'psx_routing'             => __DIR__ . '/routes',

	// Path to the cache folder
	'psx_path_cache'          => __DIR__ . '/cache',

	// Path to the library folder
	'psx_path_library'        => __DIR__ . '/src',

	// Class name of the error controller
	//'psx_error_controller'    => null,

	// If you only want to change the appearance of the error page you can 
	// specify a custom template
	//'psx_error_template'      => null,

);
