<?php
namespace maissoftware\sms;
use Yii;

class Installer {

    /**
     * Post Package Install function for Composer install
     */
    public static function postPackageInstall(){
        //Sets the alias in the project's extention.php file for correct namespace linking
        Yii::setAlias("@maissoftware/sms", __DIR__);
    }
}