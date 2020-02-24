<?php

namespace pso\yii2\oauth\models;

use yii\helpers\StringHelper;
use OAuth2\Storage\ScopeInterface;
use yii2mod\rbac\models\AuthItemModel;

class Scope extends AuthItemModel implements ScopeInterface
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules[] = [['name'], 'filter', 'filter' => [$this,'sanitizeName'], 'skipOnEmpty' => true];
        return $rules;
    }

    public function sanitizeName(){
        if(!StringHelper::startsWith($this->name, '[Scope]')){
            return "[Scope]$this->name";
        }
        return $this->name;
    }

    public function getItems(): array {
        $items = parent::getItems();
        foreach($items as $key => $val){
            foreach($items[$key] as $name => $type){
                if($type === 'permission' && !StringHelper::startsWith($name, '[Scope]')){
                    unset($items[$key][$name]);
                }
            }
        }
        return $items;
    }

    public function getDefaultScope($client_id = null)
    {
        return NULL;
    }

    public function scopeExists($scope, $client_id = null)
    {
        return false;
    }
}