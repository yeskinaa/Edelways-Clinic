<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Events */

$this->title = 'Редагування запису №' . $model->id;
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<section class="events-update">

    <h2 class="dashboard-title"><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_update-form', [
        'model' => $model,
    ]) ?>

</section>
