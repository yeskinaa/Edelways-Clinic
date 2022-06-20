<?php


use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запис на прийом';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<section class="events-booking">

    <h2 class="dashboard-title"><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_booking-form', [
        'model' => $model,
        'speciality' => $speciality,
    ]) ?>

</section>
