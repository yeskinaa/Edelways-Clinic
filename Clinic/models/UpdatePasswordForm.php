<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UpdatePasswordForm is the model behind the contact form.
 */
class UpdatePasswordForm extends Model
{
    public $password;
    public $repeat;

    public function rules()
    {
        return [
            [['password', 'repeat'], 'required'],
            [['password'], 'string', 'min' => 5],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'repeat' => 'Повторіть пароль',
        ];
    }

    public function coincidence()
    {
        if ($this->password == $this->repeat) {
            return true;
        } else {
            return false;
        }
    }
}
