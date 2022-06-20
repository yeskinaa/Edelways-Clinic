<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Speciality */

$this->title = 'Редагування спеціальності: ' . $model->title;
$this->params['breadcrumbs'][] = [
    'label' => 'Спеціальності',
    'url' => ['index']
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<section class="speciality-update">

    <h2 class="dashboard-title"><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</section>
