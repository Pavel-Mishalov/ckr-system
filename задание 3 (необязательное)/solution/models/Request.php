<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "request".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $text
 * @property string $created_at
 *
 * @property User $user
 */
class Request extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'text'], 'trim'],
            [['phone'], 'default', 'value' => null],
            [['user_id', 'name', 'email', 'text'], 'required'],
            [['user_id'], 'integer'],
            [['text'], 'string', 'max' => 5000],
            [['name', 'email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 32],
            [['email'], 'email'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Получатель',
            'name' => 'Ваше имя',
            'email' => 'Email',
            'phone' => 'Телефон',
            'text' => 'Текст заявки',
            'created_at' => 'Дата',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
