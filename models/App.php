<?php

namespace pso\yii2\oauth\models;

use Yii;
use pso\yii2\oauth\models\Model;
use yii\base\Exception;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%app}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $owner_id
 * @property string $name
 * @property string $private_key
 * @property string $public_key
 * @property int $trusted
 * @property int $multi_user
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property $owner
 * @property $user
 */
class App extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%app}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
            BlameableBehavior::className()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['!user_id', 'owner_id', 'trusted', 'multi_user', '!created_by', '!updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 65],
            [['!private_key', '!public_key'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['private_key'], 'unique'],
            [['public_key'], 'unique'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->identity, 'targetAttribute' => ['owner_id' => 'id']],
            // [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'owner_id' => 'App Owner ID',
            'name' => 'Name',
            'private_key' => 'Private Key',
            'public_key' => 'Public Key',
            'trusted' => 'Trusted',
            'multi_user' => 'Multi-User',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne($this->identity, ['id' => 'owner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne($this->identity, ['id' => 'user_id']);
    }

    public function generateKeys(){
        $key = bin2hex(random_bytes(32));
        $env = '';
        if(!YII_ENV_PROD){
            $env = "_".YII_ENV;
        }
        $this->private_key = "PRI$env-".substr($key, 32)."-X";
        $this->public_key = "PUB$env-".substr($key, 0, 32)."-X";
        return $this;
    }

    /**
     * {@inheritdoc}
     * @return \pso\yii2\oauth\models\query\AppQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \pso\yii2\oauth\models\query\AppQuery(get_called_class());
    }

    public function create(){
        if(!$this->validate()){
            return false;
        }
        $isolated = false;
        $db = SELF::getDb();
        $transaction  = $db->getTransaction();
        if(is_null($transaction)){
            $isolated = true;
            $transaction = $db->beginTransaction(\yii\db\Transaction::SERIALIZABLE);
        }
        try {
            $client = call_user_func_array([$this->identity, 'createAppUser'], [$this]);
            if(!$client->save(false)){
                throw new Exception('Could not initialize new api client');
            }
            $this->generateKeys();
            $this->user_id = $client->id;
            if(!$this->save(false)){
                throw new Exception('Could not create new app');
            }
            if($isolated){
                $transaction->commit();
            }
            return true;
        } catch (\Throwable $th) {
            if($isolated){
                $transaction->rollBack();
            }
            throw $th;
        }
    }
}
