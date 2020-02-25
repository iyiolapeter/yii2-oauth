<?php

namespace pso\yii2\oauth\models;

use OAuth2\Storage\ClientCredentialsInterface;
use pso\yii2\base\traits\PsoParamTrait;
use pso\yii2\oauth\helpers\TimestampHelper;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "oauth_client".
 *
 * @property string $title
 * @property string $description
 * @property string|null $logo
 * @property string $client_id
 * @property string|null $client_secret
 * @property int $user_id
 * @property int|null $auth_user_id
 * @property string $grant_types
 * @property string|null $redirect_uri
 * @property int $trusted
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property OauthAccessToken[] $oauthAccessTokens
 * @property OauthAuthorizationCode[] $oauthAuthorizationCodes
 * @property $authUser
 * @property $user
 * @property OauthRefreshToken[] $oauthRefreshTokens
 */
class OauthClient extends \yii\db\ActiveRecord implements ClientCredentialsInterface
{
    use \pso\yii2\oauth\traits\ARClientCredentialTrait;
    use PsoParamTrait;

    private $_user;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oauth_clients';
    }

    public function init(){
        parent::init();
        $this->_user = SELF::coalescePsoParams(['oauth.user.class','user.class']);
    }

    public function behaviors()
    {
        return [
            BlameableBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'value' => function(){
                    return TimestampHelper::toDatetime(time());
                }
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'client_id', 'user_id', 'grant_types'], 'required'],
            [['user_id', 'auth_user_id', 'trusted', 'created_by', 'updated_by'], 'integer'],
            [['grant_types', 'redirect_uri'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 65],
            [['description', 'logo', 'client_secret'], 'string', 'max' => 255],
            [['client_id'], 'string', 'max' => 32],
            [['client_id'], 'unique'],
            [['auth_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->_user, 'targetAttribute' => ['owner_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->_user, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Title',
            'description' => 'Description',
            'logo' => 'Logo',
            'client_id' => 'Client ID',
            'client_secret' => 'Client Secret',
            'user_id' => 'User ID',
            'auth_user_id' => 'Auth User ID',
            'grant_types' => 'Grant Types',
            'redirect_uri' => 'Redirect Uri',
            'trusted' => 'Trusted',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[OauthAccessTokens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOauthAccessTokens()
    {
        return $this->hasMany(OauthAccessToken::className(), ['client_id' => 'client_id']);
    }

    /**
     * Gets query for [[OauthAuthorizationCodes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOauthAuthorizationCodes()
    {
        return $this->hasMany(OauthAuthorizationCode::className(), ['client_id' => 'client_id']);
    }

    /**
     * Gets query for [AuthUser].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthUser()
    {
        return $this->hasOne($this->_user, ['id' => 'auth_user_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne($this->_user, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[OauthRefreshTokens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOauthRefreshTokens()
    {
        return $this->hasMany(OauthRefreshToken::className(), ['client_id' => 'client_id']);
    }
}
