<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception$exception */

use yii\helpers\Html;

$this->title = $name;
?>
<section class="error container">
    <?= Html::img('@web/img/get-started/image.svg', ['class' => 'error__img img-fluid', 'alt' => 'Виникла помилка', 'width' => 480, 'height' => 346])?>
    <h2 class="error__title"><?= nl2br(Html::encode($name)) ?></h2>
    <p class="error__description"><?= nl2br(Html::encode($message)) ?> Наведена помилка сталася під час обробки Вашого запиту веб-сервером.</p>
    <a class="error__link" href="<?=Yii::$app->urlManager->createUrl(['/site/index'])?>" rel="home">На головну</a>
</section>