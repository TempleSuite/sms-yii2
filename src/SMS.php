<?php

namespace maissoftware\sms;

/**
 * sms module definition class
 */
class SMS extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'maissoftware\sms\controllers';

    public static $sid = '';
    public static $token = '';
    public static $notifyServiceSid;
    public static $twilioNumber = '';
    public static $phoneColumn = '';
    public static $phoneTable = '';
    public static $phoneUserIdColumn = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }

    public function getSid(){
        return SMS::$sid;
    }

    public function setSid($sid){
        SMS::$sid = $sid;
    }

    public function getToken(){
        return SMS::$token;
    }

    public function setToken($token){
        SMS::$token = $token;
    }

    public function getNotifyServiceSid(){
        return SMS::$notifyServiceSid;
    }

    public function setNotifyServiceSid($notifyServiceSid){
        SMS::$notifyServiceSid = $notifyServiceSid;
    }

    public function getTwilioNumber(){
        return SMS::$twilioNumber;
    }

    public function setTwilioNumber($twilioNumber){
        SMS::$twilioNumber = $twilioNumber;
    }

    public function getPhoneColumn(){
        return SMS::$phoneColumn;
    }

    public function setPhoneColumn($phoneColumn){
        SMS::$phoneColumn = $phoneColumn;
    }

    public function getPhoneTable(){
        return SMS::$phoneTable;
    }

    public function setPhoneTable($phoneTable){
        SMS::$phoneTable = $phoneTable;
    }

    public function getPhoneUserIdColumn(){
        return SMS::$phoneUserIdColumn;
    }

    public function setPhoneUserIdColumn($phoneUserIdColumn){
        SMS::$phoneUserIdColumn = $phoneUserIdColumn;
    }

}
