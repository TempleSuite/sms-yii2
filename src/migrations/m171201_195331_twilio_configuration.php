<?php

use yii\db\Migration;
use yii\db\Schema;

class m171201_195331_twilio_configuration extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%twilio_configuration}}', [
            'id' => Schema::TYPE_PK,
            'sid' => Schema::TYPE_STRING . ' NOT NULL',
            'token' => Schema::TYPE_STRING . ' NOT NULL',
            'notify_service_sid' => Schema::TYPE_STRING,
            'twilio_number' => Schema::TYPE_STRING . ' NOT NULL',
        ]/*, $tableOptions*/);

    }

    public function safeDown()
    {
        $this->dropTable('{{%twilio_configuration}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171201_195331_twilio_configuration cannot be reverted.\n";

        return false;
    }
    */
}
