<?php

use yii\db\Migration;

class m260926_161746_insert_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%user}}', ['alias', 'name', 'email', 'phone', 'about'], [
            ['ivanov', 'Иван Иванов', 'ivanov@example.com', '+7 912 345-67-89', 'Отдел продаж'],
            ['anna', 'Анна Петрова', 'petrova@example.com', '+7 903 111-22-33', 'Бухгалтерия, счета и акты'],
            ['sysadmin', 'Сергей Сидоров', 'admin@example.com', null, 'Сисадмин'],
            ['olga85', 'Ольга Смирнова', 'olga85@example.com', '+7 926 555-44-11', 'Дизайнер'],
            ['kuznetsov', 'Алексей Кузнецов', 'kuznetsov@example.com', '+7 999 000-12-34', null],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user}}', ['alias' => ['ivanov', 'anna', 'sysadmin', 'olga85', 'kuznetsov']]);
    }
}
