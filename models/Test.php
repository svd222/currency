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
class Test extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency_test}}';
    }
    
}
