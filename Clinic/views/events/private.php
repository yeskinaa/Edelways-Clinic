<?php

use app\models\UserInfo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Прийоми';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<section class="events-private">

    <h2 class="dashboard-title"><?= Html::encode($this->title) ?></h2>

    <div class="control-btns">
        <?= Html::a('Календар', ['my-calendar'], ['class' => 'btn btn-warning']) ?>
    </div>

    <hr>

    <div class="table-responsive">
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'speciality',
                'label' => 'Інформація',
                'format' => 'html',
                'value' => function ($data) {
                    return '<i class="bx bxs-info-circle"></i> ' . $data['speciality'] . '<br>' .
                           '<i class="bx bxs-building-house"></i> ' . $data['room'] . '<br>' ;
                },
            ],
            [
                'attribute' => 'doctor_name',
                'label' => 'Лікар',
                'format' => 'html',
                'value' => function ($data) {
                    if (($user_info = UserInfo::findOne(['user_id' => $data['doctor_id']])) !== null){
                        $img = Html::img('@web/img/users/' . $user_info->photo, ['class' => 'rounded','width' => 150, 'height' => 150, 'alt' => $data['doctor_name']]);
                    } else {
                        $img = Html::img('@web/img/user.png', ['width' => 150, 'height' => 150, 'alt' => 'Аватар']);
                    }
                    return $img . '<br><br>' . $data['doctor_name'] . '<br>';
                },
            ],
            [
                'attribute' => 'date_time',
                'label' => 'Дата/час',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data['status'] == 'Підтверджено') {
                        return $data['date_time'] . '<br><br>' . Html::a('Талон', null, ['href' => '/events/ticket/' . $data['id'], 'class' => 'btn btn-dark', 'target' => '_blank', 'data-pjax' => "0"]);
                    } else {
                        return $data['date_time'];
                    }

                },
            ],
            [
                'attribute' => 'description',
                'format' => 'html',
                'value' => function ($data) {
                    return '<div class="text-left">' . $data['description'] . '</div>';
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($data) {
                    if($data['status'] == 'Новий запис') {
                        return '<span class="badge badge-primary">' . $data['status'] . '</span>';
                    } elseif ($data['status'] == 'Підтверджено') {
                        return '<span class="badge badge-success">' . $data['status'] . '</span>';
                    } else {
                        return '<span class="badge badge-danger">' . $data['status'] . '</span>';
                    }

                },
            ],
            'created_at',
            [
                'class' => ActionColumn::className(),
                'template' => '{update} {patient-delete} {link}',
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
    </div>

</section>
