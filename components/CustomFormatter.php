<?php
namespace app\components;

use yii\i18n\Formatter;

class CustomFormatter extends Formatter {    
    private $asArray = false;
    /**
     * Converts Json Currency Rates to array of object | array of array
     * 
     * @param string $json raw json string
     * @returns array The array of (object | array) if $json is right json string OR null otherwise
     * 
     * Right json formats are:
     * [ { "USD": 1 }, { "EUR": 2 }, { "RUR": 3 } ] 
     * { "rates" : [{ "symbol": "USD", "rate":1 }, { "symbol": "EUR", "rate":2 }, { "symbol": "RUR", "rate":3 }] }
     * 
     * @returns array if string is correct json or empty array otherwise
     */
    public function asCurrencyRates($json) {
        $cRates = [];
        $json = trim($json);
        if(preg_match('/^\{\s+?\"rates\"\s+?:/', $json)) {
            $value = json_decode($json);
            $cRates = $value->rates;
        } else {
            $pattern = '/\"([a-z]+)\":\s+([0-9])+(\\.[0-9]+)?/mis';
            preg_match_all($pattern,$json,$matches);
            foreach($matches[1] as $k=>$v) {
                $symbol = $matches[1][$k];
                $rate = $matches[2][$k];
                if($matches[3][$k]) {
                    $rate .= $matches[3][$k];
                }
                if(!$this->asArray) {
                    $obj = new \stdClass();
                    $obj->symbol = $symbol;
                    $obj->rate = (double)$rate;
                    $v = $obj;
                } else {
                    $v = ['symbol' => $symbol,'rate' => (double)$rate];
                }
                $cRates[] = $v;
            }
        }        
        return $cRates;
    }
    
    public function asArray() {
        $this->asArray = true;
        return $this;
    }
}
