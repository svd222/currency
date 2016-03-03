<?php
namespace app\validators;
class  CustomUrlValidator extends \yii\validators\UrlValidator {
    
    public $pattern = '/^{schemes}:\/\/(([A-Z0-9][A-Z0-9_-]*)(\.?[A-Z0-9][A-Z0-9_-]*)+)(?::\d{1,5})?(?:$|[?\/#])/i';
    
    public function init() {
        $this->message = \Yii::t('app/currency','{value} is not a valid URL');
        parent::init();
    }
}
