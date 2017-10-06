<?php

namespace maissoftware\sms\models;

use Yii;

/**
 * This is the model class for table "phone_number".
 *
 * @property integer $entity_id
 * @property string $phone_number
 * @property string $extension
 * @property string $table_name
 * @property string $description
 */
class PhoneNumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phone_number';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_id', 'phone_number', 'table_name'], 'required'],
            [['entity_id'], 'integer'],
            [['phone_number', 'table_name'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 12],
            [['description'], 'string', 'max' => 455],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'entity_id' => 'Entity ID',
            'phone_number' => 'Phone Number',
            'extension' => 'Extension',
            'table_name' => 'Table Name',
            'description' => 'Description',
        ];
    }
}
