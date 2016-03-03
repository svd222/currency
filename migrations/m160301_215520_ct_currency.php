<?php

use yii\db\Migration;

class m160301_215520_ct_currency extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%currency}}', [
            'symbol' => $this->string(5)->notNull()->unique(),
            'rate' => $this->money(19,4)->notNull(),
        ],' ENGINE=MyIsam DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');        
        $this->addPrimaryKey('PK_currency', '{{%currency}}', ['symbol']);
    }

    public function down()
    {
        $this->dropTable('{{%currency}}');
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
