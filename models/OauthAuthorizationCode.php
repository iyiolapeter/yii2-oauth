<?php

namespace pso\yii2\oauth\models;

use Yii;
use OAuth2\Storage\AuthorizationCodeInterface;

/**
 * This is the model class for table "oauth_authorization_code".
 *
 * @property string $authorization_code
 * @property int $user_id
 * @property string $client_id
 * @property string|null $scope
 * @property string|null $redirect_uri
 * @property string|null $expires
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property OauthClient $client
 * @property User $user
 */
class OauthAuthorizationCode extends \yii\db\ActiveRecord implements AuthorizationCodeInterface
{
    use \pso\yii2\oauth\traits\ARAuthorizationCodeTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oauth_authorization_codes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['authorization_code', 'user_id', 'client_id'], 'required'],
            [['user_id'], 'integer'],
            [['scope', 'redirect_uri'], 'string'],
            [['expires', 'created_at', 'updated_at'], 'safe'],
            [['authorization_code'], 'string', 'max' => 40],
            [['client_id'], 'string', 'max' => 32],
            [['authorization_code'], 'unique'],
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
            'authorization_code' => 'Authorization Code',
            'user_id' => 'User ID',
            'client_id' => 'Client ID',
            'scope' => 'Scope',
            'redirect_uri' => 'Redirect Uri',
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
