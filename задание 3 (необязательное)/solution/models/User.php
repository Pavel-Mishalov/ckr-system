<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $alias
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $about
 *
 * @property Request[] $requests
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alias', 'name', 'email', 'phone', 'about'], 'trim'],
            [['phone', 'about'], 'default', 'value' => null],
            [['alias', 'name', 'email'], 'required'],
            [['about'], 'string'],
            [['alias', 'phone'], 'string', 'max' => 32],
            [['name', 'email'], 'string', 'max' => 255],
            [['alias'], 'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => 'Алиас может содержать только латинские буквы и цифры.'],
            [['alias'], 'match', 'pattern' => '/^request$/i', 'not' => true, 'message' => 'Этот алиас использовать нельзя.'],
            [['alias'], 'unique'],
            [['email'], 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Алиас',
            'name' => 'Имя',
            'email' => 'Email',
            'phone' => 'Телефон',
            'about' => 'О себе',
        ];
    }

    /**
     * Gets query for [[Requests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::class, ['user_id' => 'id']);
    }
}
