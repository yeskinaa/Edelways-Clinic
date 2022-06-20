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

$this->title = 'Спеціальності';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<section class="speciality-index">

    <h2 class="dashboard-title"><?= Html::encode($this->title) ?></h2>

    <div class="control-btns">
        <?php
        Modal::begin([
            'title' => 'Додати спеціальність',
            'toggleButton' => [
                'label' => 'Додати',
                'tag' => 'button',
                'class' => 'btn btn-success',
            ],
            'size' => 'modal-md'
        ]);
        ?>

        <?php $form = ActiveForm::begin() ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

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

            'title',

            [
                'class' => ActionColumn::className(),
                'template' => '{update} {delete} {link}',
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</section>
