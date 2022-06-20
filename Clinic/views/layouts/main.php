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
        <nav class="nav" id="nav">
            <ul class="nav__list" itemscope itemtype="http://schema.org/SiteNavigationElement">
                <li class="nav__item"  itemprop="name">
                    <a class="nav__link nav__link--active" href="#home" itemprop="url">Головна</a>
                </li>
                <li class="nav__item"  itemprop="name">
                    <a class="nav__link" href="#instruction" itemprop="url">Інструкція</a>
                </li>
                <li class="nav__item"  itemprop="name">
                    <a class="nav__link" href="#statistics" itemprop="url">Статистика</a>
                </li>
                <li class="nav__item"  itemprop="name">
                    <a class="nav__link" href="#contact" itemprop="url">Контакти</a>
                </li>
            </ul>
        </nav>
        <div class="header__btns">
            <?php if(Yii::$app->user->isGuest): ?>
                <a class="btn btn__main btn__main--sky" href="<?= Yii::$app->urlManager->createUrl(['/site/login']) ?>">Вхід</a>
            <?php else: ?>
                <a class="btn btn__main btn__main--sky" href="<?= Yii::$app->urlManager->createUrl(['/user/profile']) ?>">Кабінет</a>
            <?php endif; ?>
            <div class="nav-toggle" id="nav-toggle">
                <div class="nav-toggle__hamburger" id="hamburger">
                    <span class="nav-toggle__ham nav-toggle__ham--top"></span>
                    <span class="nav-toggle__ham nav-toggle__ham--middle"></span>
                    <span class="nav-toggle__ham nav-toggle__ham--bottom"></span>
                </div>
            </div>
        </div>
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
