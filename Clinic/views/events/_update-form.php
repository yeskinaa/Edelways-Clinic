<?php

use app\models\UserInfo;
use kartik\datetime\DateTimePicker;
use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Events */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="update-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?php if(($model->doctor_id == Yii::$app->user->identity->id) || (Yii::$app->user->identity->id == 1)): ?>
            <div class="col-lg-3">
                <?= $form->field($model, 'status')->dropDownList(
                    [
                        'Підтверджено' => 'Підтверджено',
                        'Відхилено' => 'Відхилено',
                    ]);
                ?>
            </div>
        <?php endif;?>

        <?php if(($model->patient_id == Yii::$app->user->identity->id) && ($model->status != 'Підтверджено')): ?>
            <div class="col-lg-3">
                <?= $form->field($model, 'date_time')->widget(DateTimePicker::className(),[
                    'type' => DateTimePicker::TYPE_INPUT,
                    'convertFormat' => true,
                    'language' => 'uk',
                    'pluginOptions' => [
                        'format' => 'yyyy-MM-dd hh:i',
                        'autoclose' => true,
                        'weekStart' => 1,
                        'startDate' => '2022.01.01 00:00',
                    ],
                ]); ?>
            </div>
        <?php elseif (($model->doctor_id == Yii::$app->user->identity->id) || (Yii::$app->user->identity->id == 1)): ?>
            <div class="col-lg-3">
                <?= $form->field($model, 'date_time')->widget(DateTimePicker::className(),[
                    'type' => DateTimePicker::TYPE_INPUT,
                    'convertFormat' => true,
                    'language' => 'uk',
                    'pluginOptions' => [
                        'format' => 'yyyy-MM-dd hh:i',
                        'autoclose' => true,
                        'weekStart' => 1,
                        'startDate' => '2022.01.01 00:00',
                    ],
                ]); ?>
            </div>
        <?php endif;?>

        <div class="col-12">
            <?= $form->field($model, 'description')->widget(CKEditor::className(),[
                'editorOptions' => [
                    'language' => 'uk',
                    'preset' => 'basic',
                    'inline' => false,
                ],
            ]); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
