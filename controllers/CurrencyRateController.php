<?php

namespace app\controllers;

use Yii;
use app\models\Currency;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\CurrencyRatesForm;
use yii\validators\UrlValidator;
use app\helpers\UrlContentHelper;
use app\components\CustomFormatter;

/**
 * CurrencyRateController inserts new currency rates
 */
class CurrencyRateController extends Controller
{
    public function actionLoad() {
        $model = new CurrencyRatesForm;
        $post = Yii::$app->request->post();
        $validator = null;
        
        if($model->load($post) && $model->validate()) {
            $formatter = Yii::$app->formatter;
            if($post['mode'] == CurrencyRatesForm::SOURCE_URL) {
                $validator = new UrlValidator([
                    'attributes' => ['source']
                ]);
                $url = $post['source'][0];
                $model->source = $url;
                
                $validator = new \app\validators\CustomUrlValidator([
                    'attributes' => ['source'],
                ]);
                
                if($validator->validate($url)) {
                    $content = UrlContentHelper::getContent($url);
                    if($content) {
                        $cRates = $formatter->asJsonCurrencyRates($content,CustomFormatter::FORMAT_JSON_SEQUENCE);
                    }
                } else {
                    $validator->addError($model,'source',Yii::t('app/currency', 'Type the valid URL'));
                }
            } else {
                if($post['mode'] == CurrencyRatesForm::SOURCE_LOCAL) {
                    $tmpFile = $_FILES['source']['tmp_name'][0];
                    $content = file_get_contents($tmpFile);
                    $cRates = $formatter->asJsonCurrencyRates($content,CustomFormatter::FORMAT_JSON_MAP);
                }
            }
            $this->_uploadBatch($cRates);
            return $this->redirect('load');
        } else {
            
        }
        return $this->render('load',[
            'model' => $model,
        ]);
    }
    
    private function _uploadBatch(&$cRates) {
        $success = 0;
        $cRatesCount = count($cRates);
        $reason = '';
        $key = 'successInsertCurrencyRates';
        $message = 'The currency rates has been successfully inserted';

        if($cRatesCount) {
            $tablePrefix = Yii::$app->db->tablePrefix;
            if(Yii::$app->db->driverName == 'mysql') {
                $insert = $update = $test = [];
                $insertSql = $updateSql = '';
                $sql = '';
                foreach($cRates as $v) {
                    $test[] = $v->symbol;
                }
                $update = Currency::findAll($test);
                if(!empty($update)) {
                    $updateSql = " 
                        CREATE TEMPORARY TABLE `".$tablePrefix."currency_temp` (
                            `symbol` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
                            `rate` decimal(19,4) NOT NULL,
                            PRIMARY KEY (`symbol`),
                            UNIQUE KEY `symbol` (`symbol`)
                          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;\n

                        INSERT INTO ".$tablePrefix."currency_temp(`symbol`,`rate`) VALUES 
                    ";

                    foreach($update as $k=>$v) {
                        $updateSql .= "('".$v->symbol."',". $this->findRateBySymbol($v->symbol, $cRates)."),";
                    }
                    $updateSql = substr($updateSql,0,strlen($updateSql) - 1).";\n";
                    $updateSql .= "
                        UPDATE ".$tablePrefix."currency INNER JOIN "
                            .$tablePrefix."currency_temp ON ".$tablePrefix."currency.symbol = ".$tablePrefix."currency_temp.symbol ".
                            "SET ".$tablePrefix."currency.rate = ".$tablePrefix."currency_temp.rate;\n";

                }
                if(count($update) != count($cRates)) {
                    foreach($cRates as $k=>$v) {
                        $found = false;
                        foreach($update as $kk=>$vv) {
                            if($v->symbol == $vv->symbol) {
                                $found = true;
                                break;
                            }
                        }
                        if(!$found) {
                            $insert[] = $v;
                        }
                    }
                    $insertSql = "INSERT INTO ".$tablePrefix."currency(`symbol`,`rate`) VALUES ";
                    foreach($insert as $k=>$v) {
                        $insertSql .= "('".$v->symbol."',".$v->rate."),";
                    }
                    $insertSql = substr($insertSql,0,strlen($insertSql) - 1).";\n";
                } 
                $sql .= $updateSql.$insertSql;
                $quries = explode(';', $sql);

                $db = Yii::$app->db;
                $transaction = $db->beginTransaction();
                try {
                    foreach ($quries as $k=>$v) {
                        if(trim($v) != '') {
                            $db->createCommand($v)->execute();
                        }
                    }
                    $transaction->commit();
                }
                catch(\Exception $e) {
                    $transaction->rollBack();
                    $key = 'failInsertCurrencyRates';
                    $message = 'Updaiting fails {reason}'; 
                    $reason = $e->getCode().' '.$e->getMessage();
                    throw $e;
                }

                if(!$reason) {
                    $success = count($update) + count($insert);
                    $summary = '('.count($update).' updated/'.count($insert).' inserted'.')';
                }
            } else {
                throw new \yii\base\InvalidConfigException('Not supported DB',1,null);
            }
        } else {
            $key = 'emptyData';
            $message = 'The data is empty';
        }
        if(!$reason) {
            $params = [
                'counters' => '('.$success.'/'.$cRatesCount.')',
            ];
        } elseif($reason) {
            $params = [
                'counters' => '('.$success.'/'.$cRatesCount.')',
                'reason' => $reason
            ];
        }
        if($summary) {
            $message .= ' {summary}';
            $params['summary'] = $summary;
        }
        Yii::$app->session->setFlash($key, Yii::t('app/currency',$message. ' {counters}', $params));
        if($summary) {
            return true;
        }
    }
    
    protected function findRateBySymbol($sym, &$arr) {
        foreach($arr as $k=>$vv) {
            if($sym == $vv->symbol) {
                return $vv->rate;
            }
        }
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
