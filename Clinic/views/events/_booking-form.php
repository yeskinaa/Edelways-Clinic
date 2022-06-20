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

<div class="booking-form">
    <?php if((UserInfo::findOne(['user_id' => Yii::$app->user->identity->id])) !== null): ?>
        <div class="booking-form__alert"></div>

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'speciality')->dropDownList(
                    ArrayHelper::map($speciality, 'id', 'title'),
                    [
                        'prompt' => 'Оберіть спеціальність...'
                    ]);
                ?>
            </div>

            <div class="col-lg-3">
                <?= $form->field($model, 'doctor_id')->dropDownList(
                    [
                        'prompt' => 'Оберіть лікаря...',

                    ], ['disabled' => 'disabled']);
                ?>
            </div>

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
                    'options' => ['disabled' => 'disabled']
                ]); ?>
            </div>

            <div class="col-lg-2">
                <?= $form->field($model, 'room')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>
            </div>

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
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            Ви не можете записатися до лікаря поки не заповните всю інформацію в своєму профілі. Перейдіть в <a href="/user/profile" class="alert-link">Мій профіль</a> та натисніть <strong>Редагувати</strong>.
        </div>
    <?php endif;?>


</div>
