<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Yii::$app->name . ' | ' . Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header class="header" id="header">
    <div class="header__container container">
        <h1 class="brand">
            <a class="brand__link" href="<?= Yii::$app->urlManager->createUrl(['/site/index']) ?>">
                <?= Html::img('@web/img/edelways_logo.svg', ['class' => 'brand__logo', 'alt' => 'Логотип' . Yii::$app->name, 'width' => 135, 'height' => 35]) ?>
            </a>
        </h1>
    </div>
</header>

<main role="main">
    <?= $content ?>
</main>

<footer class="footer container">
    <ul class="social-media">
        <li class="social-media__item">
            <a class="social-media__link" href="#">
                <?= Html::img('@web/img/facebook.svg', ['class' => 'social-media__icon', 'alt' => Yii::$app->name . 'в Facebook', 'width' => 18, 'height' => 18]) ?>
            </a>
        </li>
        <li class="social-media__item">
            <a class="social-media__link" href="#">
                <?= Html::img('@web/img/instagram.svg', ['class' => 'social-media__icon', 'alt' => Yii::$app->name . 'в Instagram', 'width' => 18, 'height' => 18]) ?>
            </a>
        </li>
        <li class="social-media__item">
            <a class="social-media__link" href="#">
                <?= Html::img('@web/img/youtube.svg', ['class' => 'social-media__icon', 'alt' => Yii::$app->name . 'в Youtube', 'width' => 18, 'height' => 18]) ?>
            </a>
        </li>
        <li class="social-media__item">
            <a class="social-media__link" href="#">
                <?= Html::img('@web/img/twitter.svg', ['class' => 'social-media__icon', 'alt' => Yii::$app->name . 'в Twitter', 'width' => 18, 'height' => 18]) ?>
            </a>
        </li>
    </ul>

    <small class="footer__copy">
        &copy; <?= date('Y') ?> <a class="footer__link" href="<?= Yii::$app->urlManager->createUrl(['/']) ?>"><?= Yii::$app->name ?></a> - Всі права захищено
    </small>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
