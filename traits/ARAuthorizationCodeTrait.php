<?php

namespace pso\yii2\oauth\traits;

trait ARAuthorizationCodeTrait
{
    public function getAuthorizationCode($code){
        return static::find()->asArray()->where(['authorization_code' => $code])->one();
    }

    public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null){
        $model = new static([
            'authorization_code' => $code,
            'client_id' => $client_id,
            'user_id' => $user_id,
            'redirect_uri' => $redirect_uri,
            'expires' => $expires,
            'scope' => $scope
        ]);
        if(!$model->save()){
            throw new \yii\base\Exception('Authorization code not saved');
        }
        return true;
    }

    public function expireAuthorizationCode($code){
        return static::deleteAll(['authorization_code' => $code]);
    }
}