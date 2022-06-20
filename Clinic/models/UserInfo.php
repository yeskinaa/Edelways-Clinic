<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_info".
 *
 * @property int $id ID
 * @property int $user_id Користувач
 * @property string $sex Стать
 * @property string $birthday День народження
 * @property string $phone Телефон
 * @property string $photo Фото
 */
class UserInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'sex', 'birthday', 'phone'], 'required'],
            [['user_id'], 'integer'],
            [['sex'], 'string'],
            [['birthday'], 'string', 'max' => 10],
            [['phone'], 'string', 'max' => 20],
            [['photo'], 'string', 'max' => 50],
            [['user_id'], 'unique'],
            [['phone'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Користувач',
            'sex' => 'Стать',
            'birthday' => 'День народження',
            'phone' => 'Телефон',
            'photo' => 'Фото',
        ];
    }
}
