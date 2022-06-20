<?php

use app\models\AuthAssignment;
use app\models\Speciality;
use yii\bootstrap4\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use app\models\UserInfo;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $user->name;
$this->params['breadcrumbs'][] = [
    'label' => 'Всі користувачі',
    'url' => ['all']
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
\yii\web\YiiAsset::register($this);
?>
<section class="user-view">
    <h2 class="dashboard-title"><?= Html::encode($this->title) ?></h2>

    <div class="control-btns">
        <?php if((AuthAssignment::findOne(['user_id' => $user->id, 'item_name' => 'doctor'])) !== null): ?>
            <?php
            Modal::begin([
                'title' => 'Редагування інформації про лікаря',
                'options' => ['tabindex' => ''],
                'toggleButton' => [
                    'label' => 'Редагувати',
                    'tag' => 'button',
                    'class' => 'btn btn-primary',
                ],
                'size' => 'modal-lg',
            ]);
            ?>

            <?php $form = ActiveForm::begin() ?>

            <div class="row">
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
        <?php endif;?>
        <?= Html::a('Змінити статус', ['status', 'id' => $user->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => 'Ви впевнені, що хочете змінити статус користувача?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Видалити', ['delete', 'id' => $user->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Ви впевнені, що хочете видалити цього користувача?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <hr>

    <div class="row">
        <div class="user-card col-12 col-lg-7">
            <?php $user_img = (isset($user_info) && ($user_info->photo != null) && ($user_info->photo != '')) ?  'users/' . $user_info->photo : 'user.png' ?>

            <?= Html::img('@web/img/' . $user_img, ['class' => 'user-card__img img-fluid', 'width' => 200, 'height' => 200, 'alt' => 'Аватар користувача']) ?>
            <div class="user-card__info">
                <?= DetailView::widget([
                    'model' => $user,
                    'attributes' => [
                        [
                            'attribute' => 'name',
                            'label' => 'ПІБ:',
                            'value' => function ($data) {
                                return $data['name'];
                            },
                        ],
                        [
                            'label' => 'Дата народження:',
                            'format' => 'html',
                            'value' => function ($data) {
                                if(($ui = UserInfo::findOne(['user_id' => $data['id']])) !== null) {
                                    return $ui->birthday;
                                } else {
                                    return '<p class="text-danger"><i class="bx bxs-info-square"></i> Не задано...</p>';
                                }
                            },
                        ],
                        [
                            'label' => 'Стать:',
                            'format' => 'html',
                            'value' => function ($data) {
                                if(($ui = UserInfo::findOne(['user_id' => $data['id']])) !== null) {
                                    return $ui->sex;
                                } else {
                                    return '<p class="text-danger"><i class="bx bxs-info-square"></i> Не задано...</p>';
                                }
                            },
                        ],
                        [
                            'label' => 'Телефон:',
                            'format' => 'html',
                            'value' => function ($data) {
                                if(($ui = UserInfo::findOne(['user_id' => $data['id']])) !== null) {
                                    return Html::a($ui->phone, null, ['href' => 'tel:' . $ui->phone]);
                                } else {
                                    return '<p class="text-danger"><i class="bx bxs-info-square"></i> Не задано...</p>';
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
                            'label' => 'Статус:',
                            'format' => 'html',
                            'value' => function ($data) {
                                return $data['status'] == 10 ? '<span class="badge badge-success">Активний</span>' : '<span class="badge badge-danger">Заблокований</span>';
                            },
                        ],
                    ],
                ]) ?>
            </div>
        </div>

        <?php if((AuthAssignment::findOne(['user_id' => $user->id, 'item_name' => 'doctor'])) !== null): ?>
        <div class="col-12 col-lg-5">

            <?= DetailView::widget([
                'model' => $doctor_info,
                'attributes' => [
                    [
                        'attribute' => 'speciality_id',
                        'label' => 'Спеціальність:',
                        'value' => function ($data) {
                            if (($speciality = Speciality::findOne(['id' => $data['speciality_id']])) !== null) {
                                return $speciality->title;
                            } else {
                                return 'NaN';
                            }

                        },
                    ],
                    [
                        'attribute' => 'room',
                        'label' => 'Кабінет:',
                        'value' => function ($data) {
                            return $data['room'];
                        },
                    ],
                    [
                        'attribute' => 'working_days',
                        'label' => 'Робочі дні:',
                        'format' => 'html',
                        'value' => function ($data) {
                            $res = '';
                            $days = ['Понеділок', 'Вівторок', 'Середа', 'Четверг', 'П\'ятниця', 'Субота', 'Неділя'];

                            foreach ($data['working_days'] as $item) {
                                $res .= $days[$item - 1] . '<br>';
                            }

                            return $res;
                        },
                    ],
                    [
                        'attribute' => 'working_time',
                        'label' => 'Робочий час:',
                        'value' => function ($data) {
                            return $data['working_time'];
                        },
                    ],
                ],
            ]) ?>
        </div>
        <?php endif; ?>
    </div>

</section>
