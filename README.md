#####Version 1.0

## Dependencies

* A Twilio account for sms service. [Sign Up](https://www.twilio.com/try-twilio)
* [Twilio PHP Helper Library](https://www.twilio.com/docs/libraries/php)
* [Select2 Widget](http://demos.krajee.com/widget-details/select2)
* [Grid Module](http://demos.krajee.com/grid)
* [yii2-ajaxcrud](http://www.yiiframework.com/extension/yii2-ajaxcrud/)

## Installation

The recommended method for installing sms-yii2 is via Composer.  This will also install 
the dependencies automatically.  To be able to install this package, you need to tell your project's 
composer.json to search this repository.  To do that, add the following to your composer.json file.
```php
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:TempleSuite/sms-yii2.git"
    }
],
```
Then you can run the following command: 
``composer require maissoftware/sms-yii2 "dev-master"`` <br>
\* NOTE: If you do not have access to this private package with the use of an SSH key, the 
installation will fail.

#### Configuration
* Add the module to your project config file.  For example, "project/frontend/config/main.php"
```php
'sms' => [
    'class' => 'maissoftware\sms\SMS', //Path to module
    'phoneColumn' => 'phone_number', //The name of your phone column in the user table in database
    'phoneTable' => 'phone_number', //Used if phone numbers are stored in a different table in database
    'phoneUserIdColumn' => 'entity_id', //The name of the user id column in the phone table
],
```
* Use the create view to add your Twilio account information to your database
* The Grid module dependency also needs to be added to your configuration file if it is not already there.
```php
'gridview'=> [
    'class' => '\kartik\grid\Module'
],
```
* make sure you also run the migrations to create the required tables.
    * Migration will only work if you have a "user" table with the id column "id"