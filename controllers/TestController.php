<?php
namespace app\controllers;

use app\models\Test;
use yii\web\Controller;

class TestController extends Controller {
    
    public function actionIndex() {
        $test = Test::findAll(['USD','EUR']);
        var_dump($test);
        
        $condition = ['not in', 'symbol', ['USD', 'EUR']];
        $test = Test::find()->where($condition)->all();
        var_dump($test);
        
        return $this->renderContent('simple str');
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

