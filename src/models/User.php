<?php

namespace maissoftware\sms\models;

//use common\models\User;


class User extends \common\models\User
{
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullNameByLastName(){
        return $this->last_name . ", " . $this->first_name;
    }

}