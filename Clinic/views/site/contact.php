<?php

/** @var yii\web\View $this */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\LinkPager;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$title = 'Контакти';
$keywords = 'EdelWays Clinic, контакти, телефон, email, адреса, зворотній зв\'язок';
$description = 'Сторінка з контактоною інформацією EdelWays Clinic. На даній сторінці ви можете переглянути нашу контактну інформацію та написати нам через форму зворотнього зв\'язку.';

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
<section class="contact-info">
    <div class="container">
        <div class="contact-info__wrap">
            <div class="contact-info__items">
                <div class="contact-info__item">
                    <i class="contact-info__item-ico bx bxs-map"></i>
                    <div class="contact-info__item-title">Адреса</div>
                    <div class="contact-info__item-value">м. Миколаїв <br> Велика Морська, 74/3</div>
                </div>

                <div class="contact-info__item">
                    <i class="contact-info__item-ico bx bxs-phone"></i>
                    <div class="contact-info__item-title">Телефон</div>
                    <div class="contact-info__item-value">+380-800-307-733 <br> +380-800-308-733</div>
                </div>

                <div class="contact-info__item">
                    <i class="contact-info__item-ico bx bxs-envelope"></i>
                    <div class="contact-info__item-title">E-mail</div>
                    <div class="contact-info__item-value">support@gmail.com <br> admin@gmail.com</div>
                </div>
            </div>
            <div class="contact-info__feedback">

                <h3 class="contact-info__title">Надішліть нам повідомлення</h3>
                <p class="contact-info__description">Маєте зауваження або побажання? Заповніть форму нижче! </p>

                <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

                    <p class="info-message">Дякуємо, що звернулися до нас. Ми відповімо вам якомога швидше.</p>

                <?php endif; ?>

                <?php $form = ActiveForm::begin([
                    'id' => 'contact-form',
                    'options' => [
                        'class' => 'contact-info__form form'
                    ],
                ]); ?>

                <?= $form->field($model, 'name')->textInput(['placeholder' => $model->getAttributeLabel('name') . ':'])->label(false) ?>

                <?= $form->field($model, 'email')->textInput(['placeholder' => $model->getAttributeLabel('email') . ':'])->label(false) ?>

                <?= $form->field($model, 'phone')->textInput(['placeholder' => $model->getAttributeLabel('phone') . ':'])->label(false) ?>

                <?= $form->field($model, 'body')->textarea(['placeholder' => $model->getAttributeLabel('body') . ':', 'rows' => 4])->label(false) ?>

                <div class="form-group">
                    <?= Html::submitButton('Надіслати', ['class' => 'form__btn btn', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</section>
