<?php
namespace app\components;

use yii\i18n\Formatter;
use yii\base\Exception;

class CustomFormatter extends Formatter {
    const FORMAT_JSON_MAP = 'json_map';
    const FORMAT_JSON_SEQUENCE = 'json_sequence';
    
    /**
     * @param string $value raw json string
     * @param string $format CustomFormatter::FORMAT_JSON_MAP | CustomFormatter::FORMAT_JSON_SEQUENCE
     * @return array The array of object with public $rate & $symbol properties if $value is right json string OR null otherwise
     * 
     * FORMAT_JSON_MAP raw string example: [ { "USD": 1 }, { "EUR": 2 }, { "RUR": 3 } ] 
     * FORMAT_JSON_SEQUENCE raw string example: { "rates" : [{ "symbol": "USD", "rate":1 }, { "symbol": "EUR", "rate":2 }, { "symbol": "RUR", "rate":3 }] }
     * 
     * @throws Exception if $value - the wrong json
     */
    public function asJsonCurrencyRates($value, $format = self::FORMAT_JSON_SEQUENCE) {
        $cRates = [];
        if($value) {
            if($format == self::FORMAT_JSON_SEQUENCE) {
                $value = json_decode($value);
                $cRates = $value->rates;
            } elseif($format == self::FORMAT_JSON_MAP) {
                $pattern = '/\"([a-z]+)\":\s+([0-9])+(\\.[0-9]+)?/mis';
                preg_match_all($pattern,$value,$matches);
                foreach($matches[1] as $k=>$v) {
                    $symbol = $matches[1][$k];
                    $rate = $matches[2][$k];
                    if($matches[3][$k]) {
                        $rate .= $matches[4][$k];
                    }
                    $obj = new \stdClass();
                    $obj->symbol = $symbol;
                    $obj->rate = (double)$rate;
                    $v = $obj;
                    $cRates[] = $v;
                }
            }
        } else {
            throw new Exception('Incorrect json',1,null);
        }
        return $cRates;
    }
}
