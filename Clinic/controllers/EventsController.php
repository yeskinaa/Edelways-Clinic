<?php

namespace app\controllers;

use app\models\UserInfo;
use kartik\mpdf\Pdf;
use Yii;
use app\models\DoctorInfo;
use app\models\Events;
use app\models\Speciality;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii2fullcalendar\models\Event;

/**
 * EventsController implements the CRUD actions for Events model.
 */
class EventsController extends Controller
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
                    'only' => ['booking', 'calendar', 'index', 'my-calendar', 'patients', 'private', 'update'],
                    'rules' => [
                        [
                            'actions' => ['index', 'calendar'],
                            'allow' => true,
                            'roles' => ['admin'],
                        ],
                        [
                            'actions' => ['booking', 'my-calendar', 'private', 'update'],
                            'allow' => true,
                            'roles' => ['patient'],
                        ],
                        [
                            'actions' => ['patients', 'patients-calendar'],
                            'allow' => true,
                            'roles' => ['doctor'],
                        ],
                    ],
                ],
            ]
        );
    }

    public function actionBooking()
    {
        $model = new Events();

        $speciality_id_arr = [];
        $doctor_info = DoctorInfo::find()->groupBy('speciality_id')->all();
        foreach ($doctor_info as $item) {
            array_push($speciality_id_arr, $item->speciality_id);
        }
        $speciality = Speciality::find()->where(['id' => $speciality_id_arr])->orderBy(['title' => SORT_ASC])->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->patient_id = (int) Yii::$app->user->identity->id;
                $model->patient_name = User::findOne(['id' => $model->patient_id])->name;
                $model->doctor_id = (int) $model->doctor_id;
                $model->doctor_name = User::findOne(['id' => $model->doctor_id])->name;
                $model->speciality = Speciality::findOne(['id' => $model->speciality])->title;
                $model->date_time = $model->date_time . ':00';
                $model->room = DoctorInfo::findOne(['user_id' => $model->doctor_id])->room;
                $model->description = $model->description;
                $model->status = 'Новий запис';

                $model->body_d = '<p>Нова заявка від пацієнта на сайті ' . Yii::$app->name . '</p>' .
                    '<hr>' .
                    '<p><strong>Пацієнт:</strong> ' . $model->patient_name . '</p>' .
                    '<p><strong>Стать:</strong> ' . UserInfo::findOne(['user_id' => $model->patient_id])->sex . '</p>' .
                    '<p><strong>Телефон:</strong> ' . UserInfo::findOne(['user_id' => $model->patient_id])->phone . '</p>' .
                    '<p><strong>E-mail:</strong> ' . User::findOne(['id' => $model->patient_id])->email . '</p>' .
                    '<p><strong>Дата народження:</strong> ' . UserInfo::findOne(['user_id' => $model->patient_id])->birthday . '</p>' .
                    '<p><strong>Дата/час прийому:</strong> ' . $model->date_time . '</p>' .
                    '<hr>' .
                    '<p>' . $model->description . '</p>';

                $model->body_p = '<p>Запис до лікаря на сайті ' . Yii::$app->name . '</p>' .
                    '<p><hr></p>' .
                    '<p><strong>Лікар:</strong> ' . $model->doctor_id . '</p>' .
                    '<p><strong>E-mail:</strong> ' . User::findOne(['id' => $model->doctor_id])->email . '</p>' .
                    '<p><strong>Дата/час прийому:</strong> ' . $model->date_time . '</p>' .
                    '<p><strong>Кабінет:</strong> ' . $model->room . '</p>' .
                    '<p><hr></p>' .
                    '<p>' . $model->description . '</p>';

                if ($model->save() && $model->contact(User::findOne(['id' => 1])->email)) {
                    Yii::$app->session->setFlash('success', 'Ви успішно подали заявку на прийом до лікаря. Переглянути статус можна на сторінці <a href="/events/private" class="alert-link">Прийоми</a>.');

                    return $this->redirect(['booking']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('booking', [
            'speciality' => $speciality,
            'model' => $model,
        ]);
    }

    public function actionCalendar()
    {
        $model = Events::find()->where(['status' => 'Підтверджено'])->all();

        $events = [];

        foreach ($model as $item) {
            if(($user_info = UserInfo::findOne(['user_id' => $item->patient_id])) !== null) {
                $phone = '; Телефон: ' . $user_info->phone;
                $birthday = '; Дата народження: ' . $user_info->birthday;
            } else {
                $phone = '';
                $birthday = '';
            }

            $event = new Event();
            $event->id = $item->id;
            $event->backgroundColor = '#FF5978';
            $event->borderColor = '#f5b700';
            $event->textColor = '#ffffff';
            $event->title = 'Лікар: ' . $item->doctor_name . '. Пацієнт: ' . $item->patient_name . '. Кабінет: ' . $item->room;
            $event->start = $item->date_time;
            $event->end = date("Y-m-d H:i:s", strtotime($item->date_time . ' + 20 minutes'));
            $events[] = $event;
        }



        return $this->render('calendar', [
            'events' => $events,
        ]);
    }

    public function actionIndex()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => Events::find(),
            'sort' => [
                'defaultOrder' => [
                    'date_time' => SORT_DESC,
                ]
            ],
        ]);

        Yii::$app->session->setFlash('info', 'В календарі буде відображено записи, які підтверждено.');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMyCalendar()
    {
        $user_id = Yii::$app->user->identity->id;
        $model = Events::find()->where(['patient_id' => $user_id,'status' => 'Підтверджено'])->all();

        $events = [];

        foreach ($model as $item) {
            $event = new Event();
            $event->id = $item->id;
            $event->backgroundColor = '#FF5978';
            $event->borderColor = '#f5b700';
            $event->textColor = '#ffffff';
            $event->title = $item->doctor_name . ' (' . $item->speciality . ') - ' . $item->room;
            $event->start = $item->date_time;
            $event->end = date("Y-m-d H:i:s", strtotime($item->date_time . ' + 20 minutes'));
            $events[] = $event;
        }



        return $this->render('my-calendar', [
            'events' => $events,
        ]);
    }

    public function actionPatientDelete($id)
    {

        $model = $this->findModel($id);

        if($model->patient_id == Yii::$app->user->identity->id) {
            $model->delete();

            Yii::$app->session->setFlash('success', 'Запис успішно видалено.');
        }

        return $this->redirect(['private']);
    }

    public function actionPatients()
    {
        $id = Yii::$app->user->identity->id;

        $dataProvider = new ActiveDataProvider([
            'query' => Events::find()->where(['doctor_id' => $id]),
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_ASC,
                    'date_time' => SORT_DESC,
                ]
            ],
        ]);

        Yii::$app->session->setFlash('info', 'В календарі буде відображено записи, які підтверждено.');

        return $this->render('patients', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPatientsCalendar()
    {
        $user_id = Yii::$app->user->identity->id;
        $model = Events::find()->where(['doctor_id' => $user_id,'status' => 'Підтверджено'])->all();

        $events = [];

        foreach ($model as $item) {
            if(($user_info = UserInfo::findOne(['user_id' => $item->patient_id])) !== null) {
                $phone = '; Телефон: ' . $user_info->phone;
                $birthday = '; Дата народження: ' . $user_info->birthday;
            } else {
                $phone = '';
                $birthday = '';
            }

            $event = new Event();
            $event->id = $item->id;
            $event->backgroundColor = '#FF5978';
            $event->borderColor = '#f5b700';
            $event->textColor = '#ffffff';
            $event->title = $item->patient_name . ' ' . $phone . ' ' . $birthday;
            $event->start = $item->date_time;
            $event->end = date("Y-m-d H:i:s", strtotime($item->date_time . ' + 20 minutes'));
            $events[] = $event;
        }



        return $this->render('patients-calendar', [
            'events' => $events,
        ]);
    }

    public function actionPrivate()
    {
        $id = Yii::$app->user->identity->id;

        $dataProvider = new ActiveDataProvider([
            'query' => Events::find()->where(['patient_id' => $id]),
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_ASC,
                    'date_time' => SORT_DESC,
                ]
            ],
        ]);

        Yii::$app->session->setFlash('info', 'В календарі буде відображено записи, які підтверждено.');

        return $this->render('private', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTicket($id)
    {
        $user_id = Yii::$app->user->identity->id;

        if(($event = Events::findOne(['id' => $id, 'status' => 'Підтверджено', 'patient_id' => $user_id])) !== null) {
            if(($user = User::findOne(['id' => $user_id])) !== null) {
                $email = $user->email;
            } else {
                $email = 'NaN';
            }

            if(($user_info = UserInfo::findOne(['user_id' => $user_id])) !== null) {
                $birthday = $user_info->birthday;
                $phone = $user_info->phone;
                $sex = $user_info->sex;
            } else {
                $birthday = 'NaN';
                $phone = 'NaN';
                $sex = 'NaN';
            }

            if(($duser_info = UserInfo::findOne(['user_id' => $event->doctor_id])) !== null) {
                $doc_phone = $duser_info->phone;
            } else {
                $doc_phone = 'NaN';
            }


            $content = '
            <h2 class="text-center">Талон №'. $id .'</h2>
            <br>
            <h3 class="text-left"><strong>Дата/час прийому:</strong> ' . $event->date_time . ' | <strong>Кабінет:</strong> ' . $event->room . '</h3>
            <br>
            <br>
            <p class="text-left"><strong>ПІБ пацієнта:</strong> ' . $event->patient_name . ' | <strong>стать:</strong> ' . $sex . '</p>
            <p class="text-left"><strong>Дата народження:</strong> ' . $birthday . '</p>
            <p class="text-left"><strong>Телефон пацієнта:</strong> ' . $phone . '</p>
            <p class="text-left"><strong>E-mail пацієнта:</strong> ' . $email . '</p>
            <p class="text-left">' . $event->description . '</p>
            <br>
            <br>            
            <p class="text-left"><strong>ПІБ лікаря:</strong> ' . $event->doctor_name . ' | <strong>відділ:</strong> ' . $event->speciality . '</p>
            <p class="text-left"><strong>Телефон лікаря:</strong> ' . $doc_phone . '</p>
            <br>
            <br>
            <p class="text-огіешан"><small>Талон, що видано пацієнту - одноразовий та діє лише у день та час запису. З ним ви можете потрапити до вашого лікаря без черги. Потрібно підійтии до кабінету, що вказано в талоні у вказаний час та показати цей талон.</small></p>
        ';

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_LETTER,
                'orientation' => Pdf::ORIENT_LANDSCAPE,
                'destination' => Pdf::DEST_DOWNLOAD,
                'content' => $content,
                'options' => ['title' => 'ТАЛОН ДЛЯ ВІДВІДУВАННЯ ЛІКАРНІ ' . Yii::$app->name],
                'methods' => [
                    'SetTitle' => 'ТАЛОН ДЛЯ ВІДВІДУВАННЯ ЛІКАРНІ ' . Yii::$app->name,
                    'SetSubject' => 'ТАЛОН ДЛЯ ВІДВІДУВАННЯ ЛІКАРНІ ' . Yii::$app->name,
                    'SetHeader'=>['ТАЛОН ДЛЯ ВІДВІДУВАННЯ ЛІКАРНІ ' . Yii::$app->name . ' - ' . $event->date_time],
                    'SetFooter' => ['Роздруковано: ' . date('Y-m-d H:i:s')],
                ]
            ]);

            return $pdf->render();
        } else {
            throw new NotFoundHttpException('Запитуваної сторінки не існує.');
        }
    }

    public function actionUpdate($id)
    {
        $user_id = Yii::$app->user->identity->id;
        $model = $this->findModel($id);

        if(($model->patient_id == $user_id) || ($model->doctor_id == $user_id) || ($user_id == 1)) {
            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {

                Yii::$app->session->setFlash('success', 'Запис успішно відредаговано.');

                return $this->refresh();
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }

        throw new NotFoundHttpException('Запитуваної сторінки не існує.');
    }

    protected function findModel($id)
    {
        if (($model = Events::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитуваної сторінки не існує.');
    }
}
