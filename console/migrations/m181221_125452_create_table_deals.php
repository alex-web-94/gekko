<?php

use yii\db\Migration;

/**
 * Class m181221_125452_create_table_deals
 */
class m181221_125452_create_table_deals extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transactions}}', [
            'id' => $this->primaryKey(),
            'ticket' => $this->integer()->notNull()->comment('Номер транзакции'),
            'open_time' => $this->timestamp()->comment('Время открытия'),
            'type' => $this->integer()->notNull()->comment('Тип транзакции'),
            'close_time' => $this->timestamp()->comment('Время закрытия'),
            'profit' => $this->float()->comment('Прибыль'),
        ]);
        $this->createIndex('i_transactions_ticket', '{{%transactions}}', 'ticket', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('i_transactions_ticket', '{{%transactions}}');
        $this->dropTable('{{%transactions}}');
    }

}
