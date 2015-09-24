<?php

use yii\db\Schema;
use yii\db\Migration;

class m150924_084145_data_Tweets extends Migration
{
    public function up()
    {
        $this->createTable('data_Tweets', [
            'id' => 'pk',
            'tweet_id' => Schema::TYPE_STRING . ' NOT NULL',
            'lat' => Schema::TYPE_STRING . ' NOT NULL',
            'lng' => Schema::TYPE_STRING . ' NOT NULL',
            'user_name' => Schema::TYPE_STRING .  ' CHARACTER SET utf8 NOT NULL',
            'user_screen_name' => Schema::TYPE_STRING . ' NOT NULL',
            'user_img' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_TEXT . ' CHARACTER SET utf8 NOT NULL',
            'text' => Schema::TYPE_TEXT . ' CHARACTER SET utf8 NOT NULL',
        ]);
    }

    public function down()
    {
        echo "m150924_084145_data_Tweets cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
