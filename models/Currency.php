<?php
namespace app\models;

use Yii;
use yii\base\Event;

/**
 * This is the model class for table "{{%currency}}".
 *
 * @property string $symbol
 * @property string $rate
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['symbol', 'rate'], 'required'],
            [['rate'], 'number'],
            [['symbol'], 'string', 'max' => 5],
            [['symbol'], 'unique'],
        ];
    }
    
    protected function precisionRate() {
        $this->rate = $this->rate * 10000;
        $this->rate = floor($this->rate)/10000;
        $strVal = ''.$this->rate;
        if(strstr('.',$strVal)) {
            $strVal = substr($strVal,0,  strpos('.', $strVal));
            $this->rate = intval($strVal)/10000;
        }
    }
    
    public function afterFind() {
        $this->precisionRate();
        parent::afterFind();
    }
    
    public function beforeSave($insert) {
        if(parent::beforeSave($insert)) {
            $this->precisionRate();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'symbol' => Yii::t('app', 'Symbol'),
            'rate' => Yii::t('app', 'Rate'),
        ];
    }
}
