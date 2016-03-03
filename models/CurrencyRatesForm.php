<?php
namespace app\models;

use Yii;
use yii\base\Model;

class CurrencyRatesForm extends Model {
    const SOURCE_URL = 1;
    const SOURCE_LOCAL = 2;
    
    public $source;
    public $mode;
    
    public function rules() {
        return [
        ];
    }
    
    public function attributeLabels() {
        return [
            'mode' => \Yii::t('app/currency','Mode'),
            'source' => \Yii::t('app/currency','Source'),
        ];
    }
}

