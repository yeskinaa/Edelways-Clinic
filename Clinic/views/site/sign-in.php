<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$title = 'Реєстрація';
$keywords = 'EdelWays Clinic, реєстрація, e-mail, пароль';
$description = 'Сторінка реєстрація на сервісі EdelWays Clinic. Будь ласка, заповніть поля щоб створити новий акаунт.';

$this->title = $title;

$this->registerMetaTag([
    'name' => 'description',
    'content' => $description,
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $keywords,
]);

Yii::$app->seo->putOpenGraphTags(
    [
        'og:site_name' => Yii::$app->name,
        'og:title' => $title,
        'og:description' => $description,
        'og:image' => Url::to('@web/img/banner.jpg', true),
        'og:url' => Url::canonical(),
    ]
);

Yii::$app->seo->putGooglePlusMetaTags(
    [
        'name' => $title,
        'description' => $description,
        'image'  => Url::to('@web/img/banner.jpg', true),
    ]
);

$this->params['breadcrumbs'][] = [
    'label' => $title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<section class="sign-in">
    <div class="sign-in__wrap">
        <h3 class="sign-in__title">Реєстрація</h3>

        <?php if (Yii::$app->session->hasFlash('formSubmitted')): ?>

            <p class="info-message">Реєстрація прошла успішно. Тепер Ви можете перейти в свій персональний кабінет.</p>

        <?php endif; ?>

        <?php $form = ActiveForm::begin([
            'id' => 'sign-in-form',
            'options' => [
                'class' => 'sign-in__form form'
            ],
        ]); ?>

        <?= $form->field($model, 'name')->textInput(['placeholder' => $model->getAttributeLabel('name')])->label(false) ?>

        <?= $form->field($model, 'email')->textInput(['placeholder' => $model->getAttributeLabel('email')])->label(false) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label(false) ?>

        <?= $form->field($model, 'repeat')->passwordInput(['placeholder' => $model->getAttributeLabel('repeat')])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Зареєструватись', ['class' => 'login__btn btn form__btn', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <p class="login__question">Може у Вас вже є обліковий запис?</p>

        <a class="sign-in__btn btn btn__main btn__main--mood" href="<?= Yii::$app->urlManager->createUrl(['/site/login']) ?>">Увійти</a>
    </div>
</section>
