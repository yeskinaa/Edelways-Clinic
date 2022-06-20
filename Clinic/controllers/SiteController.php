<?php

namespace app\controllers;

use app\models\AuthAssignment;
use app\models\ContactForm;
use app\models\LoginForm;
use app\models\SignInForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['logout'],
                    'rules' => [
                        [
                            'actions' => ['logout'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'logout' => ['get'],
                    ],
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {

        $this->layout = 'pages.php';

        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->body = '<p><strong>ПІБ користувача:</strong> ' . $model->name . '</p>' .
                           '<p><strong>Телефон:</strong> ' . $model->phone . '</p>' .
                           '<p><strong>E-mail:</strong> ' . $model->email . '</p>' .
                           '<hr>' .
                           '<p>' . $model->body . '</p>';
            if ($model->contact(User::findOne(['id' => 1])->email)) {
                Yii::$app->session->setFlash('contactFormSubmitted');

                return $this->refresh();
            }
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays login page.
     *
     * @return string
     */
    public function actionLogin()
    {

        $this->layout = 'pages.php';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return Yii::$app->getResponse()->redirect('/user/profile');
        }

        $model->password = '';
        return $this->render('/site/login', [
            'model' => $model,
        ]);
    }

    /**
     * Displays sign-in page.
     *
     * @return string
     */
    public function actionSignIn()
    {

        $this->layout = 'pages.php';


        $model = new SignInForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->checkUser()) {
                if ($model->checkPassword()) {
                    $model->body = '<p>Дякуємо за реєстрацію на сайті ' . Yii::$app->name . '</p>' .
                                   '<p><hr> Дані для входу в кабінет:</p>' .
                                   '<p><strong>E-main:</strong> ' . $model->email . '</p>' .
                                    '<p><strong>Пароль:</strong> ' . $model->password . '</p>';

                    $user = new User();
                    $user->name = $model->name;
                    $user->email = $model->email;
                    $user->password_hash = Yii::$app->security->generatePasswordHash($model->password);
                    $user->generateAuthKey();

                    if($user->save()) {
                        $add_role = new AuthAssignment();
                        $add_role->item_name = 'patient';
                        $add_role->user_id = User::findOne(['email' => $model->email])->id;
                        $add_role->created_at = time();
                        if($add_role->save() && $model->contact(User::findOne(['id' => 1])->email)) {
                            Yii::$app->session->setFlash('formSubmitted');

                            return $this->refresh();
                        }
                    }
                } else {
                    $errorMsg= 'Паролі відрізняються.';
                    $model->addError('password', $errorMsg);
                }
            } else {
                $errorMsg= 'Користувач вже зареєстрований.';
                $model->addError('email', $errorMsg);
            }
        }

        return $this->render('/site/sign-in', [
            'model' => $model,
        ]);
    }
}
