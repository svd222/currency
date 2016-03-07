<?php

namespace app\controllers;

use Yii;
use app\models\Currency;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\CurrencyRatesForm;

/**
 * CurrencyRateController inserts new currency rates
 */
class CurrencyRateController extends Controller
{
    public function actionLoad() {
        $model = new CurrencyRatesForm;
        $post = Yii::$app->request->post();
        
        if($model->load($post) && $model->validate()) {
            $selector = Yii::$app->dataSourceSelector;
            $route = $post['source'][0]?$route = $post['source'][0]:$_FILES['source']['tmp_name'][0];
            $selector->setParams($route);
            $dataSource = $selector->select();
            
            if($dataSource->load()) {
                $data = $dataSource->getData();
                $formatter = Yii::$app->formatter;
                $cRates = $formatter->asCurrencyRates($data);
                if(!empty($cRates)) {
                    $uploaderSelector = Yii::$app->uploaderSelector;
                    $uploader = $uploaderSelector->select();
                    $uploader->setData($cRates);
                    $uploader->prepare()->upload();
                    $count = $uploader->count;
                    $insertCount = $uploader->insertCount;
                    $updateCount = $uploader->updateCount;
                    
                    Yii::$app->session->setFlash('successInsertCurrencyRates',Yii::t('app/currency','The data has been successfully updated {summary}',[
                        'summary' => ' '.Yii::t('app/currency','inserted').':'.$insertCount.', '.Yii::t('app/currency','updated').':'.
                        $updateCount.' '.Yii::t('app/currency','of').':'.$count,
                    ]));
                } else {
                    $message = Yii::t('app/currency','Currency rates is empty or incorrect format');
                    Yii::$app->session->setFlash('CurrencyRatesIsEmptyOrIncorrectFormat',$message);
                    Yii::error($message);
                }
            } else {
                $message = Yii::t('app/currency','Can`t get data from source {source}',[
                    'source' => $route,                    
                ]);
                Yii::$app->session->setFlash('CanNotGetData',$message);
                Yii::error($message);
            }
            return $this->redirect('load');
        }
        return $this->render('load',[
            'model' => $model,
        ]);
    }

    /**
     * Finds the Currency model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Currency the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Currency::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
