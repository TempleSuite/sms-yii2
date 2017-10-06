<?php

use yii\db\Migration;
use \yii\db\Schema;

class m170921_065131_sms_messages extends Migration
{
    public function safeUp()
    {
        /*$tableOptions = null;
        if($this->db->driverName === 'mysql'){
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }*/

        $tables = Yii::$app->db->schema->getTableNames();

        $this->createTable('{{%messages}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'message' => Schema::TYPE_STRING . ' NOT NULL',
            'sent_by' => Schema::TYPE_INTEGER . ' NOT NULL',
            'sent_at' => Schema::TYPE_DATETIME . ' NOT NULL'
        ]/*, $tableOptions*/);

        if(in_array('user', $tables)){
            $this->createIndex('idx-user-id', '{{%messages}}', 'user_id');
            $this->createIndex('idx-sent-by', '{{%messages}}', 'sent_by');
            $this->addForeignKey('fk-user-id', '{{%messages}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
            $this->addForeignKey('fk-sent-by', '{{%messages}}', 'sent_by', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        }
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-user-id', '{{%messages}}');
        $this->dropForeignKey('fk-sent-by', '{{%messages}}');
        $this->dropIndex('idx-user-id', '[[%messages}}');
        $this->dropIndex('idx-sent-by', '[[%messages}}');

        $this->dropTable('{{%messages}}');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170921_065131_messages cannot be reverted.\n";

        return false;
    }
    */
}
