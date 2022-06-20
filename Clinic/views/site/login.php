<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$title = 'Увійти';
$keywords = 'EdelWays Clinic, увійти, e-mail, пароль';
$description = 'Сторінка авторизації сервісу EdelWays Clinic. Будь ласка, заповніть поля e-mail та пароль, щоб увійти.';

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
<section class="login">
    <div class="login__wrap">
        <h3 class="login__title">Вас вітає клініка EdelWays!</h3>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => [
                'class' => 'login__form form'
            ],
        ]); ?>

        <?= $form->field($model, 'email')->textInput(['placeholder' => $model->getAttributeLabel('email')])->label(false) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Вхід', ['class' => 'login__btn btn form__btn', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <p class="login__question">Ще не має облікового запису?</p>

        <a class="login__btn btn btn__main btn__main--mood" href="<?= Yii::$app->urlManager->createUrl(['/site/sign-in']) ?>">Зареєструватись</a>
    </div>
</section>
