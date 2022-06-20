<?php


use yii\bootstrap4\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Лікарі';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
\yii\web\YiiAsset::register($this);
?>
<section class="user-doctors">

    <h2 class="dashboard-title"><?= Html::encode($this->title) ?></h2>

    <div class="control-btns">
        <?php
        Modal::begin([
            'title' => 'Додати лікаря',
            'options' => ['tabindex' => ''],
            'toggleButton' => [
                'label' => 'Додати',
                'tag' => 'button',
                'class' => 'btn btn-success',
            ],
            'size' => 'modal-lg',
        ]);
        ?>

        <?php $form = ActiveForm::begin() ?>

        <div class="row">
            <div class="col-12">
                <?= $form->field($doctor_info, 'user_id')->dropDownList(
                    ArrayHelper::map($users, 'id', 'name'),
                    [
                        'prompt' => 'Оберіть лікаря...'
                    ]);
                ?>
            </div>

            <div class="col-12">
                <?= $form->field($doctor_info, 'speciality_id')->dropDownList(
                    ArrayHelper::map($speciality, 'id', 'title'),
                    [
                        'prompt' => 'Оберіть спеціальність...'
                    ]);
                ?>
            </div>

            <div class="col-12">
                <?= $form->field($doctor_info, 'working_days')->checkboxList(
                    [
                        '1' => 'Пн',
                        '2' => 'Вт',
                        '3' => 'Ср',
                        '4' => 'Чт',
                        '5' => 'Пт',
                        '6' => 'Сб',
                        '7' => 'Нд',
                    ])
                ?>
            </div>

            <div class="col-12 col-lg-6">
                <?= $form->field($doctor_info, 'working_time')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-12 col-lg-6">
                <?= $form->field($doctor_info, 'room')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end() ?>

        <?php
        Modal::end();
        ?>
    </div>

    <hr>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
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
                'template' => '{view} {status} {release} {delete} {link}',
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</section>
