<?php

use yii\db\Migration;

/**
 * Class m181221_111457_create_table_file_reports
 */
class m181221_111457_create_table_file_reports extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%report_files}}', [
            'id' => $this->primaryKey(),
            'path' => $this->string()->comment('Путь'),
            'name' => $this->string()->comment('Имя'),
            'ext' => $this->string()->comment('Расширение'),
            'type' => $this->integer()->comment('Тип файла'),
            'status' => $this->integer()->defaultValue(0)->comment('Статус'),
            'original_name' => $this->string()->comment('Имя'),
            'created_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%report_files}}');
    }

}
