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
<b>Просторы для оптимизации</b><br>
1) желательно изменить немного логику <br>
Код в методе _uploadBatch (Который вызывается из actionLoad)<br>
<code>
if(count(<var>$update</var>) != count(<var>$cRates</var>)) {
    foreach(<var>$cRates</var> as <var>$k</var>=><var>$v</var>) {
        <var>$found</var> = false;
        foreach(<var>$update</var> as <var>$kk</var>=><var>$vv</var>) {
            if(<var>$v->symbol</var> == <var>$vv->symbol</var>) {
                <var>$found</var> = true;
                break;
            }
        }
        if(!<var>$found</var>) {
            <var>$insert<var/>[] = <var>$v</var>;
        }
    }
    <var>$insertSql</var> = "INSERT INTO ".<var>$tablePrefix</var>."currency(`symbol`,`rate`) VALUES ";
    foreach(<var>$insert</var> as <var>$k</var>=>$v</var>) {
        <var>$insertSql</var> .= "('".<var>$v->symbol</var>."',".<var>$v->rate</var>."),";
    }
    <var>$insertSql</var> = substr(<var>$insertSql</var>,0,strlen(<var>$insertSql</var>) - 1).";\n";
}
</code>
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