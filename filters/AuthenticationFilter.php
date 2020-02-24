<?php

namespace pso\yii2\oauth\filters;

use Yii;
use yii\base\ActionFilter;
use pso\yii2\oauth\models\OauthClient;
use pso\yii2\oauth\models\OauthAccessToken;
use OAuth2\Response;

class AuthenticationFilter extends ActionFilter
{
    public $checkScope = true;

    public $oauth = 'oauth';
    public $user = 'user';
    private $_oauth;
    private $_user;

    private $_token;
    private $_client;
    private $_identity;

    public function init(){
        parent::init();
        $this->_oauth = Yii::$app->get($this->oauth, true);
        $this->_user = Yii::$app->get($this->user, true);
    }

    public function beforeAction($action)
    {
        if(!$this->authenticate()){
            return false;
        }
        $this->setAuthority();
        if($this->checkScope){
           return $this->authorize($action);    
        }
        return true;
    }

    protected function setAuthority(){
        if(!$this->_token){
            return;
        }
        $this->_client = OauthClient::findOne($this->_token->client_id);
        $this->_oauth->setRequestClient($this->_client);
        $this->_identity = call_user_func_array([$this->_oauth->identityClass, 'findIdentity'], [$this->_token->user_id]);
        $this->_user->setIdentity($this->_identity);
    }

    public function authenticate(){
        $server = $this->_oauth->getServer();
        if (!$server->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
            $this->_oauth->prepareResponse($server->getResponse(), true);
            return false;
        }
        $token = $server->getResourceController()->getToken();
        if($token){
            $this->_token = new OauthAccessToken($token);
        }
        return true;
    }

    public function authorize($action){
        $actionId = "/".$action->getUniqueId();
        $scopes = explode(' ', $this->_token->scope??'');
        if($this->_oauth->getScopeManager()->setScopes($scopes, $this->_identity->id)->checkScopeAccess($this->_identity->id, $actionId)){
            return true;
        }
        $response  = new Response();
        $response->setError(403, 'insufficient_scope', 'The request requires higher privileges than provided by the access token');
        $this->_oauth->prepareResponse($response, true);
        return false;
    }
}