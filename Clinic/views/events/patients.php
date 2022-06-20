<?php

use app\models\User;
use app\models\UserInfo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Записи пацієнтів';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<section class="events-patients">

    <h2 class="dashboard-title"><?= Html::encode($this->title) ?></h2>

    <div class="control-btns">
        <?= Html::a('Робочий календар', ['patients-calendar'], ['class' => 'btn btn-warning']) ?>
    </div>

    <hr>

    <div class="table-responsive">
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'room',
                'label' => 'Інформація',
                'format' => 'html',
                'value' => function ($data) {
                    return '<i class="bx bxs-building-house"></i> ' . $data['room'] . '<br>' .
                           '<i class="bx bxs-phone"></i> ' . UserInfo::findOne(['user_id' => $data['patient_id']])->phone . '<br>' .
                           '<i class="bx bxs-envelope"></i> ' . User::findOne(['id' => $data['patient_id']])->email . '<br>' ;
                },
            ],
            [
                'attribute' => 'patient_name',
                'label' => 'Пацієнт',
                'format' => 'html',
                'value' => function ($data) {
                    if (($user_info = UserInfo::findOne(['user_id' => $data['patient_id']])) !== null){
                        $img = Html::img('@web/img/users/' . $user_info->photo, ['class' => 'rounded','width' => 150, 'height' => 150, 'alt' => $data['patient_name']]);
                    } else {
                        $img = Html::img('@web/img/user.png', ['width' => 150, 'height' => 150, 'alt' => 'Аватар']);
                    }
                    return $img . '<br><br>' . $data['patient_name'] . '<br> <i class="bx bx-calendar"></i> ' . UserInfo::findOne(['user_id' => $data['patient_id']])->birthday;
                },
            ],
            [
                'attribute' => 'date_time',
                'label' => 'Дата/час',
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
