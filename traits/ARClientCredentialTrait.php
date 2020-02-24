<?php

namespace pso\yii2\oauth\traits;

trait ARClientCredentialTrait
{
    public function checkClientCredentials($client_id, $client_secret = null){
        $client = static::findOne($client_id);
        if(is_null($client) || ((is_null($client_secret) || is_null($client->client_secret)) && !$this->isPublicClient($client_id))){
            return false;
        }
        return $client->client_secret === $client_secret;
    }

    public function isPublicClient($client_id){
        return false;
    }

    public function getClientDetails($client_id){
        return static::find()->where(['client_id' => $client_id])->asArray()->one();
    }

    public function getClientScope($client_id){
        return static::find()->where(['client_id' => $client_id])->select(['scope'])->column();
    }

    public function checkRestrictedGrantType($client_id, $grant_type){
        $details = $this->getClientDetails($client_id);
        if (!empty($details['grant_types'])) {
            $grant_types = explode(' ', $details['grant_types']);

            return in_array($grant_type, (array) $grant_types);
        }

        // if grant_types are not defined, then none are restricted
        return true;
    }
}