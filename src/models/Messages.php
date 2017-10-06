<?php

namespace maissoftware\sms\models;

use Yii;
//use common\models\User;
use maissoftware\sms\models\User;

/**
 * This is the model class for table "messages".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $message
 * @property integer $sent_at
 * @property integer $sent_by
 * @property User $user
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'message', 'sent_at', 'sent_by'], 'required'],
            [['user_id', 'sent_by'], 'integer'],
            [['message'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'message' => 'Message',
            'sent_at' => 'Sent At',
            'sent_by' => 'Sent By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getSentByUser()
    {
        return $this->hasOne(User::className(), ['id' => 'sent_by']);
    }

}
