<?php

namespace pso\yii2\oauth\components;

use Yii;
use yii\base\Component;
use pso\yii2\oauth\models\OauthClient;
use pso\yii2\oauth\models\OauthAccessToken;
use pso\yii2\oauth\models\OauthRefreshToken;
use pso\yii2\oauth\models\OauthAuthorizationCode;

class OauthServerComponent extends Component
{
    const EVENT_BEFORE_PREPARE_RESPONSE = 'oauthservercomp_.events.beforePrepareResponse';
    public $defaultScope = NULL;
    public $user = 'user';
    public $authManager = 'authManager';
    public $storageMap = [];
    public $grants = [];
    public $options = [];

    private $_requestClient = NULL;
    private $_user;
    private $_scopeManager;

    private $_server;
    private $_storageMap = [
        // 'authorization_code' => [
        //     'class' => OauthAuthorizationCode::class
        // ],
        'client_credentials' => [
            'class' => OauthClient::class
        ],
        'access_token' => [
            'class' => OauthAccessToken::class
        ]
        // 'refresh_token' => [
        //     'class' => OauthRefreshToken::class
        // ]
    ];
    private $_grants = [
        'authorization_code' => [
            'class' => \OAuth2\GrantType\AuthorizationCode::class
        ],
        'client_credentials' => [
            'class' => \OAuth2\GrantType\ClientCredentials::class
        ],
        'user_credentials' => [
            'class' => \OAuth2\GrantType\UserCredentials::class
        ],
        'refresh_token' => [
            'class' => \OAuth2\GrantType\RefreshToken::class
        ],
    ];

    public function init(){
        parent::init();
        $this->_user = Yii::$app->get($this->user, true);
        $this->_grants = array_merge_recursive($this->_grants, $this->grants);
        $this->_storageMap = array_merge_recursive($this->_storageMap, $this->storageMap);
        $this->_server = new \OAuth2\Server();
        $storages = [];
        foreach($this->_storageMap as $type => $config){
            $class = $config['class'];
            $storages[$type] = new $class;
            $this->_server->addStorage($storages[$type], $type);
            if(isset($this->_grants[$type])){
                $grantConfig = $this->_grants[$type];
                $grantClass = $grantConfig['class'];
                unset($grantConfig['class']);
                $grant = new $grantClass($storages[$type], $grantConfig);
            }
        }
        
    }

    public function getServer(){
        return $this->_server;
    }

    public function setRequestClient(OauthClient $client){
        $this->_requestClient = $client;
    }

    public function getIdentityClass(){
        return $this->_user->identityClass;
    }

    public function getRequestClient(){
        return $this->_requestClient;
    }

    public function getScopeManager(){
        if(!$this->_scopeManager){
            $this->_scopeManager = Yii::$app->get($this->authManager);
        }
        return $this->_scopeManager;
    }

    public function prepareResponse($response, $setParameters = false){
        $event = new \yii\base\Event();
        $event->sender = $response;
        $this->trigger(SELF::EVENT_BEFORE_PREPARE_RESPONSE,$event);
        Yii::$app->response->setStatusCode($response->getStatusCode());
        $headers = $response->getHttpHeaders();
        foreach($headers as $key => $val){
            Yii::$app->response->getHeaders()->set($key, $val);
        }
        if($setParameters){
            Yii::$app->response->data = $response->getParameters();
        }
    }

}