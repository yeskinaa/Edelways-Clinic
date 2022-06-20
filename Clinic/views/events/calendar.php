<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CoursesRequests */

$this->title = 'Всі записи';
$this->params['breadcrumbs'][] = [
    'label' => 'Прийом',
    'url' => ['private']
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="events-calendar">

    <h2 class="dashboard-title"><?= Html::encode($this->title) ?></h2>

    <?= yii2fullcalendar\yii2fullcalendar::widget([
            'events' => $events,
            'header' => [
                'left' => 'month, basicWeek, basicDay, list',
                'center' => 'title',
                'right' => 'prev, next',
            ],
            'options' => [
                'lang' => 'uk',
            ],
            'clientOptions' => [
                'weekends' => true,
                'selectHelper' => true,
                'editable' => false,
                'navLinks' => true,
                'aspectRatio' => 1.35
            ],
        ]);
    ?>


</div>
