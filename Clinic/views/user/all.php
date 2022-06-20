<?php

use app\models\AuthAssignment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Всі користувачі';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
\yii\web\YiiAsset::register($this);
?>
<section class="user-all">

    <h2 class="dashboard-title"><?= Html::encode($this->title) ?></h2>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => function ($data) {
                    if ((AuthAssignment::findOne(['item_name' => 'doctor', 'user_id' => $data['id']])) !== null) {
                        return $data['name'] . ' <span class="badge badge-dark">лікар</span>';
                    } else {
                        return $data['name'];
                    }
                },
            ],
            [
                'attribute' => 'email',
                'label' => 'E-mail:',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data['email'], null, ['href' => 'mailto:' . $data['email']]);
                },
            ],
            [
                'attribute' => 'status',
                'label' => 'Статус',
                'format' => 'html',
                'value' => function ($data) {
                    return $data['status'] == 10 ? '<span class="badge badge-success">Активний</span>' : '<span class="badge badge-danger">Заблокований</span>';
                },
            ],

            [
                'class' => ActionColumn::className(),
                'template' => '{view} {status} {delete} {link}',
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</section>
