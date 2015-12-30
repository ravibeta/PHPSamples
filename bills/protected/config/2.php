<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Adobe IT Cloud - Cluster Service',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                'ext.YiiMailer.YiiMailer',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
                        'class' => 'WebUser',
		),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		/*'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
                
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => DB_CONNECTION,
                        'emulatePrepare' => true,
                        'username' => DB_USER,
                        'password' => DB_PASSWORD,
			//'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'trace, info, error, warning',
                                        'logPath'=> LOG_PATH,
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>adminEmail,
                'enviroment'=> ENVIORNMENT,//DEV//PROD//STAGE
                'image_server'=>array('sj1'=>'sjstore.corp.adobe.com',
                                      'no1'=>'indstore.corp.adobe.com'),
                'image_server_user'=>'bmram',
                'image_server_password'=>'Bm_push1',
                'email'=>array(
                       'address'=>Email,
                       'name'=>'Adobe IT Cloud Team'
                     ),
                'web_url'=> '',
		'opsEmail' => opsEmail,
                'devEmail' => devEmail,
		'secZone' => array(
			'0'=>'Corporate(self-managed)',
			'1'=>'Front-end DMZ(self-managed)',
			'2'=>'Back-end DMZ(self-managed)',
			'3'=>'Corporate(IT-managed)'	
		),
		'flavour' =>  array(
				'small'=>array('ram'=>'4GB RAM','cpu'=>'1vCPU','disk'=>'100GB Disk'),
				'medium'=>array('ram'=>'8GB RAM','cpu'=>'2vCPU','disk'=>'100GB Disk'),
				'large'=>array('ram'=>'16GB RAM','cpu'=>'4vCPU','disk'=>'100GB Disk')
		),
		'container'=>array(
				'us-west-sj1'=>array('0'=>'DEFAULT_CORP_SJ','1'=>'DEFAULT_FEDMZ_SJ','2'=>'DEFAULT_BEDMZ_SJ','3'=>'DEFAULT_MANAGED_SJ'),
				'ap-south-no1'=>array('0'=>'DEFAULT_CORP_NOI','3'=>'DEFAULT_MANAGED_NOI')
		),
		'site_url'=>'/rajamani/mesos'
	),
);
