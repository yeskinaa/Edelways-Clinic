<?php

/** @var yii\web\View $this */

use yii\bootstrap4\Html;
use yii\helpers\Url;

$title = 'Головна';
$keywords = 'EdelWays Clinic, здоров\'я, запишіться до лікаря';
$description = 'Подбайте про здоров\'я своєї родини. Уcе в одному місці. Проконсультуйтеся з нашими медичними працівниками щодо ваших проблем.';

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
?>
<section class="home container" id="home">
    <div class="home__content">
        <div class="home__info">
            <h2 class="home__title">Подбайте про <span>здоров'я</span> своєї родини.</h2>
            <p class="home__description">Уcе в одному місці. Проконсультуйтеся з нашими медичними працівниками щодо ваших проблем.</p>
            <a class="home__btn btn btn__main btn__main--flower" href="<?= Yii::$app->urlManager->createUrl(['/site/sign-in']) ?>">Приєднатись</a>
        </div>
        <?= Html::img('@web/img/get-started/image.svg', ['class' => 'home__img img-fluid','alt' => 'Лікар з дитиною']) ?>
    </div>
    <a class="video-btn" href="https://www.youtube.com/watch?v=q0F843jV8dk" target="_blank">
        <?= Html::img('@web/img/get-started/video.svg', ['class' => 'video-btn__icon', 'alt' => 'Запустити відео', 'width' => 44, 'height' => 44]) ?>
        <span class="video-btn__info">
            <span class="video-btn__slogan">Будьте в безпеці з <?= Yii::$app->name ?></span>
            <span class="video-btn__title">Переглянути відео</span>
        </span>
    </a>
</section>

<section class="instruction container" id="instruction">
    <div class="instruction__item">
        <?= Html::img('@web/img/instruction/1.png', ['class' => 'instruction__img', 'width' => 210, 'height' => 250, 'alt' => 'Зареєструйтесь']) ?>
        <p class="instruction__text">Зареєструйтесь</p>
    </div>

    <div class="instruction__item">
        <?= Html::img('@web/img/instruction/2.png', ['class' => 'instruction__img', 'width' => 210, 'height' => 250,  'alt' => 'Запишіться до лікаря на зручний час']) ?>
        <p class="instruction__text">Запишіться до лікаря на зручний час</p>
    </div>

    <div class="instruction__item">
        <?= Html::img('@web/img/instruction/3.png', ['class' => 'instruction__img', 'width' => 210, 'height' => 250,  'alt' => 'Отримайте e-mail з електронним талоном']) ?>
        <p class="instruction__text">Отримайте електронний талон</p>
    </div>

    <div class="instruction__item">
        <?= Html::img('@web/img/instruction/4.png', ['class' => 'instruction__img', 'width' => 210, 'height' => 250,  'alt' => 'Приходьте до лікаря на обраний час']) ?>
        <p class="instruction__text">Приходьте до лікаря на обраний час</p>
    </div>
</section>

<section class="statistics" id="statistics">
    <div class="statistics__wrap">
        <div class="statistics__item">
            <span class="statistics__value">5</span>
            <p class="statistics__title">Амбулаторій</p>
        </div>

        <div class="statistics__item">
            <span class="statistics__value">15+</span>
            <p class="statistics__title">Експертів</p>
        </div>

        <div class="statistics__item">
            <span class="statistics__value">10,000+</span>
            <p class="statistics__title">Записів до лікаря</p>
        </div>
    </div>

    <div class="statistics__decor statistics__decor--first"></div>
    <div class="statistics__decor statistics__decor--second"></div>
    <div class="statistics__decor statistics__decor--third"></div>
</section>

<section class="contact container" id="contact">
    <div class="contact__content">
        <h2 class="contact__title">Зв'яжіться з <span>експертом</span>.</h2>
        <p class="contact__text">Записуйтесь на прийом та надсилайте запитання, що стосуються проблем зі здоров’ям або із записом до нашої онлайн-форми.</p>
        <a class="btn btn__main btn__main--flower" href="<?= Yii::$app->urlManager->createUrl(['/site/contact']) ?>">Проконсультуватись</a>
    </div>
    <div class="contact__video">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/AXYeWgS3UGU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></iframe>
    </div>
</section>