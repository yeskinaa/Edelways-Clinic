<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class SignInForm extends Model
{
    public $name;
    public $email;
    public $password;
    public $repeat;
    public $body;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['name', 'email', 'password', 'repeat'], 'required'],
            [['email'], 'email'],
            [['name'], 'string'],
            [['password'], 'string', 'min' => 5],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваше ПІБ',
            'email' => 'Ваш e-mail',
            'password' => 'Пароль',
            'repeat' => 'Повторіть пароль',
        ];
    }

    public function checkUser()
    {
        if (User::find()->where(['email' => $this->email])->count() == 0) {
            return true;
        }
        return false;
    }

    public function checkPassword()
    {
        if ($this->password == $this->repeat) {
            return true;
        }
        return false;
    }

    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($this->email)
                ->setFrom([$email => Yii::$app->name])
                ->setSubject('Реєстрація на сайті ' . Yii::$app->name)
                ->setHtmlBody($this->body)
                ->send();

            return true;
        }
        return false;
    }
}
