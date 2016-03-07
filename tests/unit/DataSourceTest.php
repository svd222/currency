<?php
namespace app\tests\unit;

use Yii;

class DataSourceTest extends \Codeception\TestCase\Test
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

    // tests
    public function testGetData()
    {
        //$dataSource = DataSourceFactory::getDataSource('http://localhost/rates1.json');
        $selector = Yii::$app->dataSourceSelector;
        $route = "http://localhost/rates1.json";
        $selector->setParams($route);
        $dataSource = $selector->select();    
            
        if($dataSource->load()) {
            $data = $dataSource->getData();
        }
        $this->assertEquals('{ "rates" : [{ "symbol": "UAH", "rate":1 }, { "symbol": "USD", "rate":2 }, { "symbol": "RUB", "rate":3 }] }',$data);
        
        //$dataSource = DataSourceFactory::getDataSource(Yii::getAlias('@data').DIRECTORY_SEPARATOR.'rates.json');
        $route = Yii::getAlias('@data').DIRECTORY_SEPARATOR.'rates.json';
        $selector->setParams($route);
        $dataSource = $selector->select();    
        if($dataSource->load()) {
            $data = $dataSource->getData();
        }
        $this->assertEquals('[ { "CCCW": 1 }, { "ABBB": 2 }, { "ZAAA": 3 }, {"EU": 4}, {"CUR": 5.56} ]',$data);
    }
}