#####Version 1.0

## Dependencies

* A Twilio account for sms service. [Sign Up](https://www.twilio.com/try-twilio)
* [Twilio PHP Helper Library](https://www.twilio.com/docs/libraries/php)
* [Select2 Widget](http://demos.krajee.com/widget-details/select2)
* [Grid Module](http://demos.krajee.com/grid)
* [yii2-ajaxcrud](http://www.yiiframework.com/extension/yii2-ajaxcrud/)

## Installation

The recommended method for installing sms-yii2 is via Composer.  This will also install 
the dependencies automatically.

``composer require maissoftware/sms-yii2 "@dev"``

#### Configuration
* Add the module to your project config file.  For example, "project/frontend/config/main.php"
```php
'sms' => [
    'class' => 'frontend\modules\sms\SMS', //Path to module
    'sid' => 'AC#########################', //Your Twilio Account SID
    'token' => '###############################', //Your Twilio Token
    'notifyServiceSid' => 'IS######################', //Your Twilio SMS Notify Service SID
    'twilioNumber' => '+1##########', //Your Twilio phone number
    'phoneColumn' => 'phone_number', //The name of your phone column in the user table in database
    'phoneTable' => 'phone_number', //Used if phone numbers are stored in a different table in database
    'phoneUserIdColumn' => 'entity_id', //The name of the user id column in the phone table
],
```
* The Grid module dependency also needs to be added to your configuration file if it is not already there.
```php
'gridview'=> [
    'class' => '\kartik\grid\Module'
],
```
* To use the default MVC of the module and not wrap it, change the class path to the vendor.
``class' => 'maissoftware\sms\SMS',``
* make sure you also run the migrations to create the messages table.
    * Migration will only work if you have a "user" table with the id column "id"