<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests/codeception');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'class' => 'app\components\CustomFormatter'
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=currency_test',
            'username' => 'root',
            'password' => 'YOUR_PASSWORD_HERE',
            'charset' => 'utf8',
            'tablePrefix' => 'cur_',
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
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
