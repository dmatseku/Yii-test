<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'passport_number' => $this->string()->notNull()->unique(),
            'firstname' => $this->string()->notNull(),
            'lastname' => $this->string()->notNull(),
            'date_of_birth' => $this->date()->notNull(),
            'passport_expiry_date' => $this->date()->notNull(),
            'password_hash' => $this->string()->notNull(),
            'access_token' => $this->string(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->dropTable('{{%user}}');
    }
}
