<?php

namespace maissoftware\sms\components;
use Twilio\Exceptions\TwilioException;
use yii;
use maissoftware\sms\models\Messages;
use maissoftware\sms\models\User;
use Twilio\Rest\Client;
use maissoftware\sms\SMS;
use maissoftware\sms\models\PhoneNumber;
//use yii\db\Schema;

class TwilioHelper {

    /**
     * Sends an SMSS message to a user by making a Twilio REST Request
     * @param $cellNumber string cellphone number of the user the message is being sent to
     * @param $message String message that is to be sent to the user
     * @return \Twilio\Rest\Api\V2010\Account\MessageInstance The response from Twilio
     */
    public static function sendOne($cellNumber, $message){

        $client = new Client(SMS::$sid, SMS::$token);

        //the arguments in the create function are: the cell number with '+1' prefixed, an array with the from value
        //being your twilio number and the body value your message
        return $client->messages->create('+1' . $cellNumber, ['from' => SMS::$twilioNumber, 'body' => $message]);
    }

    /**
     * Sends an SMSS message to a list of users by making a Twilio Notify Service REST Request
     * @param $users array of users the message is being sent to
     * @param $message string message that is to be sent to the list of users
     * @throws TwilioException if the $message length is less than one
     * @return \Twilio\Rest\Notify\V1\Service\NotificationInstance The response from Twilio
     */
    public static function sendGroup($users, $message){
        //Throws an exception if the message being sent is empty
        //echo strlen($message);
        //return strlen($message);
        if(strlen($message) < 1){
            throw new TwilioException();
        }
        //Creates a Client with Account SID and Token
        $client = new Client(SMS::$sid, SMS::$token);
        //Initializes the array for the toBinding attribute used in the Notify Service
        $binding = [];
        $phone = SMS::$phoneColumn;
        //$message = 'from the send group';

        foreach ($users as $user){
            if(self::checkTableNames(SMS::$phoneTable)){
                $table = Yii::$app->db->schema->getTableSchema(SMS::$phoneTable);
                $tableColumns = $table->getColumnNames();
                $phoneNumbers = self::getPhoneNumbers($user, $tableColumns);

                if(sizeof($phoneNumbers) > 0){
                    $message = 'from the send group with phoneNumbers > 0';
                    foreach ($phoneNumbers as $phoneNumber){
                        $binding[] = '{"binding_type":"sms", "address":"+1' . $phoneNumber->phone_number . '"}';
                    }
                }else{
                    $message = 'from the send group with phoneNumbers < 0';
                    $binding[] = '{"binding_type":"sms", "address":"+1' . $user->$phone . '"}';
                }
            }else{
                $message= "from the send group when a phone table does not exist";
                $binding[] = '{"binding_type":"sms", "address":"+1' . $user->$phone . '"}';
            }
        }

        return $client->notify->services(SMS::$notifyServiceSid)->notifications->create([
            "toBinding" => $binding,
            'body' => $message
        ]);
    }

    public static function checkTableNames($tableName){
        $tables = Yii::$app->db->schema->getTableNames();

        if(in_array($tableName, $tables))
            return true;
        else
            return false;
    }

    public static function getPhoneNumbers($user, $tableColumns){
        if(in_array('table_name', $tableColumns)){
            $phoneNumbers = PhoneNumber::find()->where([
                SMS::$phoneUserIdColumn => $user->id,
                'table_name' => 'user',
                'description' => 'cell'
            ])->all();
            return $phoneNumbers;
        }else{
            $phoneNumbers = PhoneNumber::find()->where([
                SMS::$phoneUserIdColumn => $user->id,
                'description' => 'cell'
            ])->all();
            return $phoneNumbers;
        }
    }
}