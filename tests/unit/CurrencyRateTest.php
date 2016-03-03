<?php
namespace app\tests\unit;
use app\models\Currency;

class CurrencyRateTest extends \Codeception\TestCase\Test
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
    
    public function testRatePrecision() {
        $post = [
            'Currency'=> ['symbol'=>'GED','rate'=>3.72847113],    
        ];
        $currency = new Currency;
        $currency->load($post);
        $currency->save();
        
        Currency::findOne(['symbol'=>'GED']);
        $this->assertEquals(3.7284, $currency->rate);
        
        $post = [
            'Currency'=> ['symbol'=>'GED','rate'=>3.7],    
        ];
        $currency = new Currency;
        $currency->load($post);
        $currency->save();
        
        Currency::findOne(['symbol'=>'GED']);
        $this->assertEquals(3.7, $currency->rate);
    }
    
    public function testMultipleSave() {
        $post = [
            'Currency'=> [
                ['symbol'=>'LS','rate'=>17.1],
                ['symbol'=>'KZ','rate'=>4.7],
            ]
        ];
        $models = [];
        foreach($post['Currency'] as $currencyData) {
            $models[] = new Currency;
        }
        Currency::loadMultiple($models, $post);
        $i = 0;
        foreach($models as $model) {
            if(!$model->save()) {
                break;
            }
            $i++;
        }
        $this->assertEquals(count($post['Currency']),$i);
    }

    public function testSave()
    {
        $currency = new Currency;
        
        $post = [
            'Currency'=> ['symbol'=>'MED','rate'=>3.7],    
        ];
        $this->assertTrue($currency->load($post), 'Load POST data');
        $this->assertTrue($currency->save(), 'Save model');
        $cKeys = array_keys(Currency::find()->indexBy('symbol')->all());
        $this->assertContains('MED',$cKeys);
        
        $allCurrency = Currency::find()->all();
        $this->assertEquals(5,count($allCurrency));
    }
    
    public function testUniqueSymbolKey() {
        $post = [
            'Currency'=> [
                ['symbol'=>'GED','rate'=>28.1],
                ['symbol'=>'GED','rate'=>3.72847113],
            ]
        ];
        $models = [];
        foreach($post['Currency'] as $currencyData) {
            $models[] = new Currency;
        }
        Currency::loadMultiple($models, $post);
        $i = 0;
        foreach($models as $model) {
            if(!$model->save()) {
                break;
            }
            $i++;
        }
        $this->assertNotEquals(count($post['Currency']),$i);
    }
    
}