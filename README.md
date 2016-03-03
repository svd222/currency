Yii 2 Basic Project Template
============================

Настройка:<br>
(Если codeception не установлен то установить)<br>
1) в корне проекта выполнить: composer update<br>
2) Сконфигурировать подключение к БД (user,pass,dbname) в 3 файлах<br>
config/console.php (компонент db)<br>
config/db.php<br>
codeception.yml<br>

Головной скрипт<br>
    http://currency/currency-rate/load<br>
Тестирование <br>
    php path/to/codeception/codecept run unit<br>

Головной контроллер CurrencyRateController<br>
Соотвественно action - load<br>

По поводу алгоритма работы:<br>
<b>Просторы для оптимизации<b><br>
1) желательно изменить немного логику <br>
Код в методе _uploadBatch (Который вызывается из actionLoad)<br>
<code>
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
<code>
дает сложность = n в 2 степени<br>
а еще лучше <br>
2) вынести код _uploadBatch в микросервис, написанный на python, golang...<br>
3) Немного корявая получилась модель CurrencyRatesForm <br>
Аттрибут $source заменить на 2: <br>
    $sourceUrl<br>
    $sourceLocal<br>
    Оба в rules задать как необязательные и в зависимости от того на какой чекбокс кликнули (грузить из локального файла данные или по урл)
проверять аттрибуты кастомными валидаторами<br>
    function sourceUrlValidate($attr, $params) (встроенный валидатор UrlValidator выдает ошибку при проверке адреса типа http://localhost) Хотя работает
верно, но для теста неудобно).<br>
    function sourceLocalValidate($attr, $params) (Или использовать built in yii\validators\FileValidator)
Так было бы элегантнее<br>