<?php

use yii\db\Migration;

/**
 * Class m241122_094447_files_table
 */
class m241122_094447_files_table extends Migration
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

        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'filepath' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-file-user_id',
            '{{%files}}',
            'user_id'
        );

        $this->addForeignKey(
            'fk-files-user_id',
            '{{%files}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->dropTable('{{%files}}');
    }
}
