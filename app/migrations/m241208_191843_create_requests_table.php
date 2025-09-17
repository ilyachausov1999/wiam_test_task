<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%requests}}`.
 */
class m241208_191843_create_requests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%requests}}', [
            'id' => $this->primaryKey()->unsigned()->comment('ID заявки'),
            'user_id' => $this->integer()->notNull()->unsigned()->comment('ID пользователя'),
            'amount' => $this->integer()->notNull()->unsigned()->comment('Сумма кредита'),
            'term' => $this->integer()->notNull()->unsigned()->comment('Срок кредита'),
            'status' => $this->integer()->notNull()->unsigned()->comment('Статус заявки'),
            'created_at' => $this->bigInteger()->notNull()->unsigned(),
            'processed_at' => $this->bigInteger()->unsigned()->null(),
        ]);

        $this->createIndex('idx-requests-status', '{{%requests}}', 'status');
        $this->createIndex('idx-requests-user_id', '{{%requests}}', 'user_id');
        $this->createIndex('idx-requests-user_id-status','{{%requests}}', ['user_id', 'status']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-requests-user_id-status','{{%requests}}');
        $this->dropIndex('idx-requests-status','{{%requests}}');
        $this->dropIndex('idx-requests-user_id','{{%requests}}');

        $this->dropTable('{{%requests}}');
    }
}
