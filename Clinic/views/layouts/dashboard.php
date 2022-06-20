<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="msapplication-TileColor" content="#f3ecb8">
    <meta name="theme-color" content="#f3ecb8">
    <?php $this->registerCsrfMetaTags() ?>
    <?= $this->registerCssFile("@web/css/dashboard.css", [
        'depends' => [\yii\bootstrap4\BootstrapAsset::class],
    ], 'dashboard');
    ?>

    <title><?= Yii::$app->name .' - '.Html::encode($this->title) ?></title>

    <?php $this->head() ?>
</head>
<body itemscope itemtype="http://schema.org/WebPage" class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
<header class="dashboard__header">
    <?php
        $words = explode(' ', Yii::$app->name);
        echo $words[0] . ' кабінет';
    ?>
</header>
<div id="dashboard-hamburger" class="dashboard__hamburger" title="Меню">
    <span class="ham top"></span>
    <span class="ham middle"></span>
    <span class="ham bottom"></span>
</div>
<nav id="dashboard-menu" class="sidebar-container">
    <ul class="sidebar-navigation">
        <li>
            <a href="<?=Yii::$app->urlManager->createUrl(['/user/profile'])?>">
                <i class="bx bxs-user-circle"></i>
                <span>Мій профіль</span>
            </a>
        </li>
        <li>
            <a href="<?=Yii::$app->urlManager->createUrl(['/events/booking'])?>">
                <i class="bx bxs-book-heart"></i>
                <span>Запис на прийом</span>
            </a>
        </li>
        <li>
            <a href="<?=Yii::$app->urlManager->createUrl(['/events/private'])?>">
                <i class="bx bxs-user-check"></i>
                <span>Особисті записи</span>
            </a>
        </li>
        <li>
            <a href="<?=Yii::$app->urlManager->createUrl(['/events/my-calendar'])?>">
                <i class="bx bxs-calendar"></i>
                <span>Особистий календар</span>
            </a>
        </li>
        <?php if(Yii::$app->user->can('doctor')): ?>
            <li>
                <a href="<?=Yii::$app->urlManager->createUrl(['/events/patients'])?>">
                    <i class="bx bxs-user-detail"></i>
                    <span>Записи пацієнтів</span>
                </a>
            </li>
            <li>
                <a href="<?=Yii::$app->urlManager->createUrl(['/events/patients-calendar'])?>">
                    <i class="bx bxs-calendar-star"></i>
                    <span>Робочий календар</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Yii::$app->user->can('admin')): ?>
            <li>
                <a href="<?=Yii::$app->urlManager->createUrl(['/events/index'])?>">
                    <i class="bx bx-list-ul"></i>
                    <span>Всі записи</span>
                </a>
            </li>
            <li>
                <a href="<?=Yii::$app->urlManager->createUrl(['/user/all'])?>">
                    <i class="bx bxs-group"></i>
                    <span>Всі користувачі</span>
                </a>
            </li>
            <li>
                <a href="<?=Yii::$app->urlManager->createUrl(['/user/doctors'])?>">
                    <i class="bx bxs-user-badge"></i>
                    <span>Лікарі</span>
                </a>
            </li>
            <li>
                <a href="<?=Yii::$app->urlManager->createUrl(['/speciality/index'])?>">
                    <i class="bx bxs-copy-alt"></i>
                    <span>Спеціальності</span>
                </a>
            </li>
        <?php endif; ?>
        <li>
            <a href="<?=Yii::$app->urlManager->createUrl(['/site/index'])?>">
                <i class="bx bx-home"></i>
                <span>Перейти на сайт</span>
            </a>
        </li>
        <li>
            <a href="<?=Yii::$app->urlManager->createUrl(['/site/logout'])?>">
                <i class="bx bx-log-out-circle"></i>
                <span>Вийти</span>
            </a>
        </li>
    </ul>
</nav>

<div class="content-container">

    <div class="container-fluid">

        <?= Alert::widget() ?>
        <?= \app\widgets\BreadcrumbsWidget::widget([
            'options' => [
                'class' => 'breadcrumb',
            ],
            'homeLink' => [
                'label' => 'Головна',
                'url' => ['/'],
                'class' => 'home',
                'template' => '<li>{link}</li>',
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'itemTemplate' => '<li>{link}</li>',
            'activeItemTemplate' => '<li class="active">{link}</li>',
            'tag' => 'ul',
            'encodeLabels' => false
        ]);
        ?>
        <?= $content ?>

    </div>
</div>

<?=
    $this->registerJsFile(
        '@web/js/dashboard.js',
        ['depends' => [\yii\web\JqueryAsset::class]]
    );
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
