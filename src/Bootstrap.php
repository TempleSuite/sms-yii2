<?php

namespace maissoftware\sms;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface {

    /**
     * Bootstrap method called during application bootstrap stage to create alias.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app) {
        Yii::setAlias("@maissoftware/sms", __DIR__);
    }

}
