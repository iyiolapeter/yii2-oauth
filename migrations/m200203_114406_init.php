<?php

namespace pso\yii2\oauth\migrations;

use pso\yii2\base\traits\PsoParamTrait;
use yii\db\Migration;

/**
 * Class m200203_114406_init
 */
class m200203_114406_init extends Migration
{
    use PsoParamTrait;
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $user = static::coalescePsoParams(['oauth.user.table','user.table']);
        $this->createTable('{{%oauth_clients}}', [
            'title' => $this->string(65)->notNull(),
            'description' => $this->string()->notNull(),
            'logo' => $this->string()->null(),
            'client_id' => $this->string(32)->unique()->notNull(),
            'client_secret' => $this->string()->null(),
            'auth_user_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'grant_types' => $this->text()->notNull(),
            'redirect_uri' => $this->text()->null(),
            'trusted' => $this->boolean()->notNull()->defaultValue(0),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
        $this->addPrimaryKey('pk_oauth_client', '{{%oauth_clients}}','client_id');
        $this->addForeignKey('fk_oauth_client_user','{{%oauth_clients}}', 'user_id', $user,'id','CASCADE', 'CASCADE');
        $this->addForeignKey('fk_oauth_client_auth_user','{{%oauth_clients}}', 'auth_user_id', $user,'id','CASCADE', 'CASCADE');
        $this->createTable('{{%oauth_authorization_codes}}', [
            'authorization_code' => $this->string(40)->unique()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'client_id' => $this->string(32)->notNull(),
            'scope' => $this->text()->null(),
            'redirect_uri' => $this->text(1000)->null(),
            'expires' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
        $this->addPrimaryKey('pk_oauth_authorization_code', '{{%oauth_authorization_codes}}','authorization_code');
        $this->addForeignKey('fk_oauth_authorization_code_client','{{%oauth_authorization_codes}}', 'client_id', '{{%oauth_clients}}','client_id','CASCADE', 'CASCADE');
        $this->addForeignKey('fk_oauth_authorization_code_user','{{%oauth_authorization_codes}}', 'user_id', $user,'id','CASCADE', 'CASCADE');
        $this->createTable('{{%oauth_access_tokens}}', [
            'access_token' => $this->string(40)->unique()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'client_id' => $this->string(32)->notNull(),
            'scope' => $this->text()->null(),
            'expires' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
        $this->addPrimaryKey('pk_oauth_access_token', '{{%oauth_access_tokens}}','access_token');
        $this->addForeignKey('fk_oauth_access_token_client','{{%oauth_access_tokens}}', 'client_id', '{{%oauth_clients}}','client_id','CASCADE', 'CASCADE');
        $this->addForeignKey('fk_oauth_access_token_user','{{%oauth_access_tokens}}', 'user_id', $user,'id','CASCADE', 'CASCADE');
        $this->createTable('{{%oauth_refresh_tokens}}', [
            'refresh_token' => $this->string(40)->unique()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'client_id' => $this->string(32)->notNull(),
            'scope' => $this->text()->null(),
            'expires' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
        $this->addPrimaryKey('pk_oauth_refresh_token', '{{%oauth_refresh_tokens}}','refresh_token');
        $this->addForeignKey('fk_oauth_refresh_token_client','{{%oauth_refresh_tokens}}', 'client_id', '{{%oauth_clients}}','client_id','CASCADE', 'CASCADE');
        $this->addForeignKey('fk_oauth_refresh_token_user','{{%oauth_refresh_tokens}}', 'user_id', $user,'id','CASCADE', 'CASCADE');
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%oauth_clients}}');
        $this->dropTable('{{%oauth_authorization_codes}}');
        $this->dropTable('{{%oauth_access_tokens}}');
        $this->dropTable('{{%oauth_refresh_tokens}}');
    }
}
