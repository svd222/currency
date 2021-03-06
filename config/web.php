<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'afNGZzze_0CE5tKHa4InTeEK0XhpsJOh',
        ],
        'dataSourceSelector' => [
            'class' => 'app\common\BaseSelector',
            'selectorFunc' => //custom this function for choose needed value and sets the below 'data' item for your own purposes
                function($data, $route) {
                    $validator = new \app\validators\CustomUrlValidator;
                    $selected = ($validator->validate($route)?1:2);
                    return \Yii::createObject(['class'=>$data[$selected]['class'],'route'=>$route]);
                },
            'data' => [
                1 => [
                    'class' => 'app\data\RemoteDataSource'
                ],
                2 => [
                    'class' => 'app\data\LocalDataSource'
                ]
            ]
        ],
        'uploaderSelector' => [
            'class' => 'app\common\BaseSelector',
            'selectorFunc' => 
                function($data, $var) {
                    $validator = new \app\validators\CustomUrlValidator;
                    if($var === null) {
                        $selected = 1;
                    }
                    return \Yii::createObject($data[$selected]);
                },
            'data' => [
                1 => [
                    'class'=>'app\data\batch\mysql\Uploader',
                    'keyField' => 'symbol',
                    'dataTable' => 'currency',
                    'fields' => ['symbol','rate'],
                ],
            ]
        ],                
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'formatter' => [
            'class' => 'app\components\CustomFormatter'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app'       => 'app.php',
                        'app/currency' => 'currency.php',
                    ],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        'urlManager' => [
	    'enablePrettyUrl' => true,
	    'showScriptName'  => false,
	    'enableStrictParsing' => true,
	    'rules' => [
                [
                    'pattern' => '',
                    'route' => 'site/index'
                ],
		[
		    'pattern' => '<controller>/<action>/<id:\d+>',
		    'route' => '<controller>/<action>',		
		],
		
		[
		    'pattern' => '<controller>/<action>',
		    'route' => '<controller>/<action>',		
		],                
	    ],
	],
    ],
    'params' => $params,
    'language' => 'ru-RU',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
