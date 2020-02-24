<?php

namespace pso\yii2\oauth\traits;

use pso\yii2\oauth\helpers\TimestampHelper;

trait ARAccessTokenTrait
{
    public function getAccessToken($oauth_token){
        $model = static::find()->asArray()->where(['access_token' => $oauth_token])->one();
        if(!is_null($model)){
            $model['expires'] = TimestampHelper::toTimestamp($model['expires']);
        }
        return $model;
    }

    public function setAccessToken($oauth_token, $client_id, $user_id, $expires, $scope = null){
        $model = new static([
            'access_token' => $oauth_token,
            'client_id' => $client_id,
            'user_id' => $user_id,
            'expires' => TimestampHelper::toDatetime($expires),
            'scope' => $scope
        ]);
        if(!$model->save()){
            throw new \yii\base\Exception('Access Token not saved'.print_r($model->getErrors(), true));
        }
        return true;
    }
}