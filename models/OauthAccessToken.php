<?php

namespace pso\yii2\oauth\models;

use OAuth2\Storage\AccessTokenInterface;
use Yii;

/**
 * This is the model class for table "oauth_access_token".
 *
 * @property string $access_token
 * @property int $user_id
 * @property string $client_id
 * @property string|null $scope
 * @property string|null $expires
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property OauthClient $client
 * @property User $user
 */
class OauthAccessToken extends \yii\db\ActiveRecord implements AccessTokenInterface
{
    use \pso\yii2\oauth\traits\ARAccessTokenTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oauth_access_tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['access_token', 'user_id', 'client_id'], 'required'],
            [['user_id'], 'integer'],
            [['scope'], 'string'],
            [['expires', 'created_at', 'updated_at'], 'safe'],
            [['access_token'], 'string', 'max' => 40],
            [['client_id'], 'string', 'max' => 32],
            [['access_token'], 'unique'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => OauthClient::className(), 'targetAttribute' => ['client_id' => 'client_id']],
            //[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'access_token' => 'Access Token',
            'user_id' => 'User ID',
            'client_id' => 'Client ID',
            'scope' => 'Scope',
            'expires' => 'Expires',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(OauthClient::className(), ['client_id' => 'client_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getUser()
    // {
    //     return $this->hasOne(User::className(), ['id' => 'user_id']);
    // }
}
