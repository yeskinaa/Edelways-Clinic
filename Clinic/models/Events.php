<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "events".
 *
 * @property int $id ID
 * @property int $patient_id ID пацієнта
 * @property string $patient_name ПІБ пацієнта
 * @property int $doctor_id ID лікаря
 * @property string $doctor_name ПІБ лікаря
 * @property string $speciality Спеціальність
 * @property string $date_time Дата та час
 * @property string $room Кабінет
 * @property string $description Примітка
 * @property string $status Статус
 * @property string $created_at Створено 
 */
class Events extends \yii\db\ActiveRecord
{
    public $body_d;
    public $body_p;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patient_id', 'patient_name', 'doctor_id', 'doctor_name', 'speciality', 'date_time', 'room'], 'required'],
            [['patient_id', 'doctor_id'], 'integer'],
            [['date_time', 'created_at'], 'safe'],
            [['description', 'status'], 'string'],
            [['patient_name', 'doctor_name', 'speciality'], 'string', 'max' => 255],
            [['room'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'patient_id' => 'ID пацієнта',
            'patient_name' => 'ПІБ пацієнта',
            'doctor_id' => 'Лікар',
            'doctor_name' => 'ПІБ лікаря',
            'speciality' => 'Спеціальність',
            'date_time' => 'Дата та час РРРР-ММ-ДД ГГ:ХХ',
            'room' => 'Кабінет',
            'description' => 'Примітка',
            'status' => 'Статус',
            'created_at' => 'Створено ',
        ];
    }

    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo(User::findOne(['id' => $this->patient_id])->email)
                ->setFrom([$email => Yii::$app->name])
                ->setSubject('Запис до лікаря на сайті ' . Yii::$app->name)
                ->setHtmlBody($this->body_p)
                ->send();

            Yii::$app->mailer->compose()
                ->setTo(User::findOne(['id' => $this->doctor_id])->email)
                ->setFrom([$email => Yii::$app->name])
                ->setSubject('Нова заявка від пацієнта на сайті ' . Yii::$app->name)
                ->setHtmlBody($this->body_d)
                ->send();

            return true;
        }
        return false;
    }
}
