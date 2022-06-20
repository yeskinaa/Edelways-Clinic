<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\imagine\Image;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class UpdateUserInfoForm extends Model
{
    public $name;
    public $birthday;
    public $sex;
    public $email;
    public $phone;
    public $image;
    public $imageFile;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['name', 'sex', 'birthday', 'email', 'phone'], 'required'],
            [['email'], 'email'],
            [['name'], 'string', 'max' => 255],
            [['imageFile'], 'image', 'extensions' => 'jpg', 'maxSize' => 1024 * 1024 * 1, 'minWidth' => 200, 'minHeight' => 200],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваше ПІБ',
            'birthday' => 'Дата народження (РРРР-ММ-ДД)',
            'sex' => 'Стать',
            'email' => 'Ваш e-mail',
            'phone' => 'Телефон',
            'imageFile' => 'Зображення',
        ];
    }

    public function checkUser($user_id)
    {
        if (User::find()->where(['email' => $this->email])->andWhere(['not', ['id' => $user_id]])->count() == 0) {
            return true;
        }
        return false;
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('img/users/' . $this->image);

            $dir = '@webroot/img/users/';
            Image::thumbnail($dir . $this->image, 200, 200)
                ->save(Yii::getAlias($dir . $this->image), ['quality' => 100]);

            return true;
        } else {
            return false;
        }
    }
}
