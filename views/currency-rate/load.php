<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CurrencyRatesForm;

/* @var $this yii\web\View */
/* @var $model app\models\Currency */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app/currency', 'Load batch of currency rates');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/currency', 'Currencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

app\assets\CurrencyAsset::register($this);
$flashMessages = \Yii::$app->session->getAllFlashes();
if(!empty($flashMessages)) {
?>
<div class="col-lg-12">
<?php
    foreach($flashMessages as $key => $message) {
?>
        <div class="alert <?php echo $key == 'successInsertCurrencyRates'?'alert-success':'alert-danger'; ?>" role="alert">
            <?= $message ?>
        </div>
<?php
    }
?>  
</div>
<?php
}
?>
<div class="currency-load">
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="currency-form">

        <?php $form = ActiveForm::begin([
            'id' => 'currency-rates-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
            'options' => [
                'enctype'=>'multipart/form-data'
            ],    
        ]); ?>

        <div class="row">
            
            <div class="col-md-7 col-lg-7">
                
                <div id="source-wrapper">
                    
                <?= $form->field($model, 'source')->textInput([
                    'maxlength' => true,
                    'name'=>'source[]'
                ]) ?>
                    
                <?= $form->field($model, 'source')->fileInput([
                    'maxlength' => true,
                    'style' => 'display:none;',
                    'name' => 'source[]'
                ])->label(false); ?>    
                
                <?= $form->field($model, 'source')->fileInput([
                    'maxlength' => true, 
                    'style' => 'display:none;',
                    //'class' => 'currencyratesform-source'
                ])->label(false) ?>
                    
                </div>
            </div>
            
            <div class="col-md-5 col-lg-5">
                
                <div id="mode-wrapper">

                    <?php echo Html::checkbox( 'mode', (empty($model->mode)?true:$model->mode == CurrencyRatesForm::SOURCE_URL?true:false), [
                        'label'=> \Yii::t('app/currency','Url'),
                        'labelOptions'=>[
                            
                        ],
                        'id' => 'currencyratesform-mode-url',
                        'value' => CurrencyRatesForm::SOURCE_URL,
                    ]) ?>
                    
                    <?php echo Html::checkbox( 'mode', (!empty($model->mode) && $model->mode == CurrencyRatesForm::SOURCE_LOCAL)?true:false, [
                        'label'=> \Yii::t('app/currency','Local'),
                        'labelOptions'=>[
                            
                        ],
                        'id' => 'currencyratesform-mode-local',
                        'value' => CurrencyRatesForm::SOURCE_LOCAL,
                    ]) ?>
                    <?php 
//                    echo $form->field($model, 'mode')->checkbox([
//                        'uncheck'=>2,
//                        'label'=> \Yii::t('app/currency','Url'),
//                        'labelOptions'=>[
//                            
//                        ],
//                        'id' => 'currencyratesform-mode-url',
//                        'value' => \app\models\CurrencyRatesForm::SOURCE_URL,
//                        'checked' => true
//                    ]);
                    ?>

                    <?php 
//                    echo $form->field($model, 'mode')->checkbox([
//                        'uncheck'=>1,
//                        'label'=> \Yii::t('app/currency','Local file'),
//                        'labelOptions'=>[
//
//                        ],
//                        'id' => 'currencyratesform-mode-local',
//                        'value' => \app\models\CurrencyRatesForm::SOURCE_LOCAL,
//                    ]); 
                    ?>
                    
                </div>
            </div>
            
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app/currency', 'Upload'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    
</div>