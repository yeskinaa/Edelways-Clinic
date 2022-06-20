<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "doctor_info".
 *
 * @property int $id id
 * @property int $user_id Користувач
 * @property int $speciality_id Спеціальність
 * @property string $room Кабінет
 * @property string $working_days Робочі дні
 * @property string $working_time Робочий час
 */
class DoctorInfo extends \yii\db\ActiveRecord
{
    public $body;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doctor_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'speciality_id', 'room', 'working_days', 'working_time'], 'required'],
            [['user_id', 'speciality_id'], 'integer'],
            [['room'], 'string', 'max' => 50],
            [['working_time'], 'string', 'max' => 15],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'user_id' => 'Користувач',
            'speciality_id' => 'Спеціальність',
            'room' => 'Кабінет',
            'working_days' => 'Робочі дні',
            'working_time' => 'Робочий час',
        ];
    }

    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo(User::findOne(['id' => $this->user_id])->email)
                ->setFrom([$email => Yii::$app->name])
                ->setSubject('Тепер ви лікар на сайті ' . Yii::$app->name)
                ->setHtmlBody($this->body)
                ->send();

            return true;
        }
        return false;
    }
}
