<?php

namespace pso\yii2\oauth;

use Yii;
use pso\yii2\base\Module as BaseModule;
use pso\yii2\oauth\components\OauthServerComponent;
use yii\di\Instance;

class Module extends BaseModule
{

    const EVENT_AUTOCOMPLETE = 'oauth_autocomplete_';

    protected static $psoId = 'oauth';

    public $oauth = 'oauth';

    private $_oauth;
    
    public function init()
    {
        parent::init();
        $this->_oauth = Instance::ensure($this->oauth, OauthServerComponent::className());
        // custom initialization code goes here
    }

    public function getHandlerComponent(){
        return $this->_oauth;
    }
}