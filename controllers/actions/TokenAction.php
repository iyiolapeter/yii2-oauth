<?php

namespace pso\yii2\oauth\controllers\actions;

use Yii;
use yii\base\Action;

class TokenAction extends Action
{
    public $oauth = 'oauth';
    private $_oauth;

    public function init(){
        $this->_oauth = Yii::$app->get($this->oauth, true);
    }

    public function run(){
        $response = $this->_oauth->getServer()->handleTokenRequest(\OAuth2\Request::createFromGlobals());
        $this->_oauth->prepareResponse($response);
        return $response->getParameters();
    }
}