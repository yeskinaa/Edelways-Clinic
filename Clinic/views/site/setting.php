<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Налаштування';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['setting']
];
?>
<div class="site-setting">
    <h2 class="section-title"><?= Html::encode($this->title) ?></h2>

    <div class="row">
        <div class="col-12 col-md-6 col-lg-8">
            <h3>Редагування e-mail</h3>
            <hr>

            <?php if (Yii::$app->session->hasFlash('updateUserForm')): ?>

                <div class="alert alert-success">
                    E-mail успішно змінено.
                </div>

            <?php endif; ?>

            <div class="catalog-create-form">

                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                <?= $form->field($model, 'email')->textInput() ?>
                <p class="text-info"><strong>На цей e-mail будуть приходити листи з форми зворотнього зв'язку.</strong></p>

                <div class="form-group">
                    <?= Html::submitButton('Змінити', ['class' => 'btn btn-info']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <h3>Редагування паролю</h3>
            <hr>

            <?php if (Yii::$app->session->hasFlash('updatePasswordForm')): ?>

                <div class="alert alert-success">
                    Пароль успішно змінено.
                </div>

            <?php endif; ?>

            <div class="setting-password-form">

                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                <?= $form->field($password, 'password')->passwordInput() ?>

                <?= $form->field($password, 'repeat')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Змінити', ['class' => 'btn btn-info']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
