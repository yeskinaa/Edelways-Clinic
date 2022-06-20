<?php

use app\models\AuthAssignment;
use app\models\Speciality;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use app\models\UserInfo;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Мій профіль';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => ['view']
];

if(Yii::$app->user->can('admin')) {
    $role = '<span>Адміністратор</span>';
} elseif(Yii::$app->user->can('doctor')) {
    $role = '<span>Лікар</span>';
} else {
    $role = '';
}

\yii\web\YiiAsset::register($this);
?>
<section class="user-profile">
    <h2 class="dashboard-title"><?= Html::encode($this->title) ?> <?= $role ?></h2>

    <div class="control-btns">
        <?php
        Modal::begin([
            'title' => 'Редагувати інформацію',
            'toggleButton' => [
                'label' => 'Редагувати',
                'tag' => 'button',
                'class' => 'btn btn-primary',
            ],
            'size' => 'modal-md'
        ]);
        ?>

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

        <?= $form->field($user_info_form, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($user_info_form, 'birthday')->textInput(['maxlength' => true]) ?>

        <?= $form->field($user_info_form, 'sex')->dropDownList(
            [
                'Чоловіча' => 'Чоловіча',
                'Жіноча' => 'Жіноча',
            ]);
        ?>

        <?= $form->field($user_info_form, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($user_info_form, 'phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($user_info_form, 'imageFile')->fileInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end() ?>

        <?php
        Modal::end();
        ?>

        <?php
        Modal::begin([
            'title' => 'Редагувати пароль',
            'toggleButton' => [
                'label' => 'Пароль',
                'tag' => 'button',
                'class' => 'btn btn-info',
            ],
            'size' => 'modal-md'
        ]);
        ?>

        <?php $form = ActiveForm::begin() ?>

        <?= $form->field($user_password_form, 'password')->passwordInput(['maxlength' => true]) ?>

        <?= $form->field($user_password_form, 'repeat')->passwordInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end() ?>

        <?php
        Modal::end();
        ?>
    </div>

    <hr>

    <div class="row">
        <div class="user-card">
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
                            'attribute' => 'specialty_id',
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
