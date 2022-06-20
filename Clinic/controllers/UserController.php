<?php

namespace app\controllers;

use app\models\AuthAssignment;
use app\models\DoctorInfo;
use app\models\Speciality;
use app\models\UpdatePasswordForm;
use app\models\UpdateUserInfoForm;
use app\models\User;
use app\models\UserInfo;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class UserController extends Controller
{
    public $layout = 'dashboard.php';

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['all', 'doctors', 'delete', 'profile', 'release', 'status', 'view'],
                    'rules' => [
                        [
                            'actions' => ['all', 'doctors', 'delete', 'release', 'status', 'view'],
                            'allow' => true,
                            'roles' => ['admin'],
                        ],
                        [
                            'actions' => ['profile'],
                            'allow' => true,
                            'roles' => ['patient'],
                        ],
                    ],
                ],
            ]
        );
    }

    public function actionAll()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->andWhere(['not', ['id' => 1]]),
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('all', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        if(($user_info = UserInfo::findOne(['user_id' => $id])) !== null) {
            $photo = $user_info->photo;
            $user_info->delete();
            if($photo == $id . '-user.jpg') {
                unlink('img/users/' . $model->photo);
            }
        }

        if(($doctor_info = DoctorInfo::findOne(['user_id' => $id])) !== null) {
            $doctor_info->delete();
        }

        $aas = AuthAssignment::findOne(['user_id' => $id]);
        $aas->delete();

        Yii::$app->session->setFlash('success', 'Користувача успішно видалено.');

        return $this->redirect(['all']);
    }



    public function actionDoctors()
    {
        $users_id = [];
        $model = AuthAssignment::find()->where(['item_name' => 'doctor'])->all();

        foreach ($model as $item) {
            array_push($users_id, $item->user_id);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['id' => $users_id])->andWhere(['not', ['id' => 1]]),
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ],
        ]);


        $doctor_info = new DoctorInfo();
        $speciality = Speciality::find()->orderBy(['title' => SORT_ASC])->all();
        $users = User::find()->andWhere(['not', ['id' => $users_id]])->andWhere(['not', ['id' => 1]])->orderBy(['name' => SORT_ASC])->all();

        if ($this->request->isPost) {
            if ($doctor_info->load($this->request->post())) {
                $doctor_info->user_id = (int) $doctor_info->user_id;
                $doctor_info->speciality_id = (int) $doctor_info->speciality_id;
                $doctor_id = $doctor_info->user_id;
                $doctor_info->working_days = implode(';', $doctor_info->working_days);

                $doctor_info->body = '<p>Тепер ви лікар на сайті ' . Yii::$app->name . '</p>' .
                                     '<p>Ви можете увійти в ваш кабінет, щоб переглянути записи від пацієнтів.</p>';

                if($doctor_info->save()  && $doctor_info->contact(User::findOne(['id' => 1])->email)) {
                    $aas = AuthAssignment::findOne(['user_id' => $doctor_id]);
                    $aas->item_name = 'doctor';
                    $aas->save();

                    Yii::$app->session->setFlash('success', 'Лікаря успішно додано в базу.');

                    return $this->refresh();
                }
            }
        }

        return $this->render('doctors', [
            'dataProvider' => $dataProvider,
            'doctor_info' => $doctor_info,
            'speciality' => $speciality,
            'users' => $users,
        ]);
    }

    public function actionGetDoctorRoom()
    {
        if (Yii::$app->request->post('doctor')) {
            $doctor = Yii::$app->request->post('doctor');
            $room = '';

            if(($doctor = DoctorInfo::findOne(['user_id' => $doctor])) !== null) {
                $room = $doctor->room;
            }

            echo $room;
        }
    }

    public function actionGetDoctors()
    {
        if (Yii::$app->request->post('speciality')) {
            $speciality = Yii::$app->request->post('speciality');
            $list = '<option value="">Оберіть лікаря...</option>';

            $all_doctors_arr = [];

            $doctor_info = DoctorInfo::find()->Where(['speciality_id' => $speciality])->all();

            $doctors_id = [];

            foreach ($doctor_info as $item) {
                array_push($doctors_id, $item->user_id);
            }

            $doctors = User::find()->where(['id' => $doctors_id, 'status' => 10])->orderBy(['name' => SORT_ASC])->all();
            foreach ($doctors as $item) {
                $all_doctors_arr[$item->id] = $item->name;
            }

            if (count($all_doctors_arr) == 0) {
                echo $list;
            } else {
                foreach ($all_doctors_arr as $key => $value) {
                    $list .= '<option value="'. $key .'">'. $value .'</option>';
                }
                echo $list;
            }
        }
    }

    public function actionGetDateTimeInfo()
    {
        if (Yii::$app->request->post('doctor')) {
            $doctor = Yii::$app->request->post('doctor');
            $working_time = '<div class="alert alert-danger" role="alert">Помилка при визначенні графіка роботи лікаря. Обновіть сторінку і спробуйте ще раз.</div>';

            if(($doctor = DoctorInfo::findOne(['user_id' => $doctor])) !== null) {
                $week = ['Понеділок', 'Вівторок', 'Середа', 'Четвер', 'П\'ятниця', 'Субота', 'Неділя'];
                $days = explode(';', $doctor->working_days);
                $days_str = '';

                foreach ($days as $item) {
                    $days_str .= $week[$item - 1] . ', ';
                }

                $days_str = mb_substr($days_str, 0, -2);

                $time = explode('-', $doctor->working_time);
                $time_start = $time[0];
                $time_finish = $time[1];
                echo '<div class="alert alert-info" role="alert"><strong>Графік роботи лікаря:</strong> ' . $days_str . ' з ' . $time_start . ' по ' . $time_finish . '.</div>';
            } else {
                echo $working_time;
            }
        }
    }

    public function actionProfile()
    {

        $user_id = Yii::$app->user->identity->id;

        $user_info_form = new UpdateUserInfoForm();
        $user_password_form = new UpdatePasswordForm();

        if ($this->request->isPost) {
            if ($user_info_form->load($this->request->post())) {
                if($user_info_form->checkUser($user_id)) {
                    $user_info_form->imageFile = UploadedFile::getInstance($user_info_form, 'imageFile');

                    if (($user = User::findOne(['id' => $user_id])) !== null) {
                        $user->name = $user_info_form->name;
                        $user->email = $user_info_form->email;

                        if ((UserInfo::findOne(['user_id' => $user_id])) !== null) {
                            $user_info = UserInfo::findOne(['user_id' => $user_id]);
                        } else {
                            $user_info = new UserInfo();
                        }

                        $user_info->user_id = $user_id;
                        $user_info->birthday = $user_info_form->birthday;
                        $user_info->sex = $user_info_form->sex;
                        $user_info->phone = $user_info_form->phone;
                    } else {
                        Yii::$app->session->setFlash('warning', 'Виникла помилка при збереженні даних.');

                        return $this->redirect(['profile']);
                    }

                    if ($user_info_form->imageFile) {
                        $user_info_form->image = $user_id . '-user.' . $user_info_form->imageFile->extension;
                        $user_info_form->upload();
                        $user_info->photo = $user_info_form->image;
                    }

                    if ($user->save() && $user_info->save()) {
                        Yii::$app->session->setFlash('success', 'Профіль успішно відредаговано.');
                    } else {
                        Yii::$app->session->setFlash('warning', 'Виникла помилка при збереженні даних.');

                        return $this->redirect(['profile']);
                    }
                } else {
                    $errorMsg= 'Користувач з таким e-mail вже існує.';
                    $user_info_form->addError('email', $errorMsg);
                }
            }

            if ($user_password_form->load($this->request->post())) {
                if ($user_password_form->coincidence()) {
                    $user = User::findOne(['id' => $user_id]);
                    $user->setPassword($user_password_form->password);
                    if ($user->save()) {
                        Yii::$app->session->setFlash('success', 'Пароль успішно змінено.');

                        return $this->refresh();
                    }
                } else {
                    Yii::$app->session->setFlash('danger', 'Помилка введених даних, паролі не співпадають. Відкрийте форму ще раз, введіть дані повторно та збережіть.');
                    $errorMsg= 'Паролі не співпадають.';
                    $user_password_form->addError('repeat', $errorMsg);
                }
            }
        }

        if (($user = User::findOne(['id' => $user_id])) !== null) {
            $user_info_form->name = $user->name;
            $user_info_form->email = $user->email;

            if (($user_info = UserInfo::findOne(['user_id' => $user_id])) !== null) {
                $user_info_form->birthday = $user_info->birthday;
                $user_info_form->sex = $user_info->sex;
                $user_info_form->phone = $user_info->phone;

                if(($doctor_info = DoctorInfo::findOne(['user_id' => $user_id])) !== null) {

                    $doctor_info->working_days = explode(';', $doctor_info->working_days);

                    return $this->render('profile', [
                        'doctor_info' => $doctor_info,
                        'user' => $user,
                        'user_info' => $user_info,
                        'user_info_form' => $user_info_form,
                        'user_password_form' => $user_password_form,
                    ]);
                } else {
                    return $this->render('profile', [
                        'user' => $user,
                        'user_info' => $user_info,
                        'user_info_form' => $user_info_form,
                        'user_password_form' => $user_password_form,
                    ]);
                }
            } else {
                Yii::$app->session->setFlash('warning', 'Для запису до лікаря, будь-ласка, заповніть всі дані. Натисніть на кнопку "Редагувати" та надішліть форму.');

                if(($doctor_info = DoctorInfo::findOne(['user_id' => $user_id])) !== null) {

                    $doctor_info->working_days = explode(';', $doctor_info->working_days);

                    return $this->render('profile', [
                        'doctor_info' => $doctor_info,
                        'user' => $user,
                        'user_info_form' => $user_info_form,
                        'user_password_form' => $user_password_form,
                    ]);
                } else {
                    return $this->render('profile', [
                        'user' => $user,
                        'user_info_form' => $user_info_form,
                        'user_password_form' => $user_password_form,
                    ]);
                }
            }


        }

        throw new NotFoundHttpException('Запитуваної сторінки не існує.');
    }

    public function actionRelease($id)
    {
        if(($model = AuthAssignment::findOne(['user_id' => $id])) !== null) {
            $model->item_name = 'patient' ;

            if($model->save()) {
                $doctor_info = DoctorInfo::findOne(['user_id' => $id]);
                $doctor_info->delete();

                Yii::$app->session->setFlash('success', 'Лікаря успішно видалено зі списку лікарів.');

                return $this->redirect(['doctors']);
            }
        }

        throw new NotFoundHttpException('Запитуваної сторінки не існує.');
    }

    public function actionStatus($id)
    {
        $model = $this->findModel($id);
        $model->status = $model->status == 10 ? 0 : 10;
        $model->save();


        return $this->redirect(['all']);
    }

    public function actionView($id)
    {

        if (($user_info = UserInfo::findOne(['user_id' => $id])) !== null) {

            if(($doctor_info = DoctorInfo::findOne(['user_id' => $id])) !== null) {

                $doctor_info->working_days = explode(';', $doctor_info->working_days);

                $speciality = Speciality::find()->orderBy(['title' => SORT_ASC])->all();
                $users = User::find()->andWhere(['not', ['id' => $id]])->andWhere(['not', ['id' => 1]])->orderBy(['name' => SORT_ASC])->all();

                if ($this->request->isPost) {
                    if ($doctor_info->load($this->request->post())) {
                        $doctor_info->user_id = $id;
                        $doctor_info->speciality_id = (int) $doctor_info->speciality_id;
                        $doctor_id = $doctor_info->user_id;
                        $doctor_info->working_days = implode(';', $doctor_info->working_days);

                        if($doctor_info->save()) {
                            $aas = AuthAssignment::findOne(['user_id' => $doctor_id]);
                            $aas->item_name = 'doctor';
                            $aas->save();

                            Yii::$app->session->setFlash('success', 'Інформацію про лікаря успішно змінено.');

                            return $this->refresh();
                        }
                    }
                }

                return $this->render('view', [
                    'doctor_info' => $doctor_info,
                    'speciality' => $speciality,
                    'users' => $users,
                    'user' => $this->findModel($id),
                    'user_info' => $user_info,
                ]);
            } else {
                return $this->render('view', [
                    'user' => $this->findModel($id),
                    'user_info' => $user_info,
                ]);
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Користувач не заповнив всі дані про себе.');

            if(($doctor_info = DoctorInfo::findOne(['user_id' => $id])) !== null) {

                $doctor_info->working_days = explode(';', $doctor_info->working_days);

                $speciality = Speciality::find()->orderBy(['title' => SORT_ASC])->all();
                $users = User::find()->andWhere(['not', ['id' => $id]])->andWhere(['not', ['id' => 1]])->orderBy(['name' => SORT_ASC])->all();

                if ($this->request->isPost) {
                    if ($doctor_info->load($this->request->post())) {
                        $doctor_info->user_id = $id;
                        $doctor_info->speciality_id = (int) $doctor_info->speciality_id;
                        $doctor_id = $doctor_info->user_id;
                        $doctor_info->working_days = implode(';', $doctor_info->working_days);

                        if($doctor_info->save()) {
                            $aas = AuthAssignment::findOne(['user_id' => $doctor_id]);
                            $aas->item_name = 'doctor';
                            $aas->save();

                            Yii::$app->session->setFlash('success', 'Інформацію про лікаря успішно змінено.');

                            return $this->refresh();
                        }
                    }
                }

                return $this->render('view', [
                    'doctor_info' => $doctor_info,
                    'speciality' => $speciality,
                    'users' => $users,
                    'user' => $this->findModel($id),
                ]);
            } else {
                return $this->render('view', [
                    'user' => $this->findModel($id),
                ]);
            }
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитуваної сторінки не існує.');
    }
}
