<?php

namespace maissoftware\sms\models;

use Yii;

/**
 * This is the model class for table "twilio_configuration".
 *
 * @property integer $id
 * @property string $sid
 * @property string $token
 * @property string $notify_service_sid
 * @property string $twilio_number
 */
class TwilioConfiguration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'twilio_configuration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'token', 'twilio_number'], 'required'],
            [['sid', 'token', 'notify_service_sid', 'twilio_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sid' => 'Sid',
            'token' => 'Token',
            'notify_service_sid' => 'Notify Service Sid',
            'twilio_number' => 'Twilio Number',
        ];
    }
}
