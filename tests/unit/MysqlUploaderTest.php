<?php
namespace app\tests\unit;

use Yii;
use app\models\Currency;

class MysqlUploaderTest extends \Codeception\TestCase\Test
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
    public function testUploader()
    {
        $obj1 = new \stdClass();
        $obj1->symbol = 'SSE';
        $obj1->rate = 3.58;
        $obj2 = new \stdClass();
        $obj2->symbol = 'ESE';
        $obj2->rate = 4.2;
        $cRates = [
            $obj1, $obj2
        ];
        
        $uploaderSelector = Yii::$app->uploaderSelector;
        $uploader = $uploaderSelector->select();
        $uploader->setData($cRates);
        $uploader = $uploader->prepare();
        $count = $uploader->count;
        $insertCount = $uploader->insertCount;
        $updateCount = $uploader->updateCount;
        $this->assertEquals(2,$count);
        $this->assertEquals(2,$insertCount);
        $this->assertEquals(0,$updateCount);
        
        $uploader->upload();
        $cRate = Currency::find()->where(['symbol' => 'ESE'])->one();
        $this->assertEquals(4.2,$cRate->rate);
        $cRate = Currency::find()->where(['symbol' => 'SSE'])->one();
        $this->assertEquals(3.58,$cRate->rate);
    }
}