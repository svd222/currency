<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
require_once __DIR__ . implode(DIRECTORY_SEPARATOR, ['', '..', 'vendor', 'autoload.php']);
require_once __DIR__ . implode(DIRECTORY_SEPARATOR, ['', '..', 'vendor', 'yiisoft', 'yii2', 'Yii.php']);
Yii::setAlias('@tests', __DIR__);
Yii::setAlias('@data', __DIR__ . DIRECTORY_SEPARATOR . '_data');

try {
    Yii::getAlias('@app');
} catch (yii\base\InvalidParamException $ex) {
    Yii::setAlias('@app', __DIR__.DIRECTORY_SEPARATOR.'..');
}

$className = 'app\models\Currency';
$classFile = Yii::getAlias('@' . str_replace('\\', '/', $className) . '.php');
Yii::$classMap['app\models\Currency'] = $classFile;