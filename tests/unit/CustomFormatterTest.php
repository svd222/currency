<?php
namespace app\tests\unit;
use Yii;
use app\components\CustomFormatter;

class CustomFormatterTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testRightFormat() {
        $json1 = '[ { "CCCW": 1 }, { "ABBB": 2 }, { "ZAAA": 3 } ]';
        $data1 = [
            ['CCCW',1],
            ['ABBB',2],
            ['ZAAA',3],
        ];
        $json2 = '{ "rates" : [{ "symbol": "ZEAL", "rate":1.3 }, { "symbol": "SRER", "rate":2.2 }, { "symbol": "SEFA", "rate":3.1 }] }';
        $data2 = [
            ['ZEAL',1.3],
            ['SRER',2.2],
            ['SEFA',3.1],
        ];
        $formatter = Yii::$app->formatter;
                
        $test1Data = $formatter->asJsonCurrencyRates($json1,CustomFormatter::FORMAT_JSON_MAP);
        $i = 0;
        foreach($test1Data as $d) {
            $this->assertTrue(is_object($d));
            $this->assertEquals($data1[$i][0], $d->symbol);
            $this->assertEquals($data1[$i][1], $d->rate);
            $i++;
        }
        
        $test2Data = $formatter->asJsonCurrencyRates($json2,CustomFormatter::FORMAT_JSON_SEQUENCE);
        
        $i = 0;
        foreach($test2Data as $d) {
            $this->assertTrue(is_object($d));
            $this->assertEquals($data2[$i][0], $d->symbol);
            $this->assertEquals($data2[$i][1], $d->rate);
            $i++;
        }
    }
}