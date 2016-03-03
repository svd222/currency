Yii 2 Basic Project Template
============================

Настройка:
(Если codeception не установлен то установить)
1) в корне проекта выполнить: composer update
2) Сконфигурировать подключение к БД (user,pass,dbname) в 3 файлах
config/console.php (компонент db)
config/db.php
codeception.yml

Головной скрипт
    http://currency/currency-rate/load
Тестирование 
    php path/to/codeception/codecept run unit

Головной контроллер CurrencyRateController
Соотвественно action - load

По поводу алгоритма работы:
Просторы для оптимизации
1) желательно изменить немного логику 
Код в методе _uploadBatch (Который вызывается из actionLoad)
if(count($update) != count($cRates)) {
    foreach($cRates as $k=>$v) {
        $found = false;
        foreach($update as $kk=>$vv) {
            if($v->symbol == $vv->symbol) {
                $found = true;
                break;
            }
        }
        if(!$found) {
            $insert[] = $v;
        }
    }
    $insertSql = "INSERT INTO ".$tablePrefix."currency(`symbol`,`rate`) VALUES ";
    foreach($insert as $k=>$v) {
        $insertSql .= "('".$v->symbol."',".$v->rate."),";
    }
    $insertSql = substr($insertSql,0,strlen($insertSql) - 1).";\n";
}
дает сложность = n в 2 степени
а еще лучше 
2) вынести код _uploadBatch в микросервис, написанный на python, golang...
3) Немного корявая получилась модель CurrencyRatesForm 
Аттрибут $source заменить на 2: 
    $sourceUrl
    $sourceLocal
    Оба в rules задать как необязательные и в зависимости от того на какой чекбокс кликнули (грузить из локального файла данные или по урл)
проверять аттрибуты кастомными валидаторами
    function sourceUrlValidate($attr, $params) (встроенный валидатор UrlValidator выдает ошибку при проверке адреса типа http://localhost) Хотя работает
верно, но для теста неудобно).
    function sourceLocalValidate($attr, $params) (Или использовать built in yii\validators\FileValidator)
Так было бы элегантнее