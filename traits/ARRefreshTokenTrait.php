<?php

namespace pso\yii2\oauth\traits;

use pso\yii2\oauth\helpers\TimestampHelper;

trait ARRefreshTokenTrait
{
    public function getRefreshToken($refresh_token){
        $model = static::find()->asArray()->where(['refresh_token' => $refresh_token])->one();
        if(!is_null($model)){
            $model['expires'] = !is_null($model['expires'])?TimestampHelper::toTimestamp($model['expires']):0;
        }
        return $model;
    }

    public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null){
        $model = new static([
            'refresh_token' => $refresh_token,
            'client_id' => $client_id,
            'user_id' => $user_id,
            'expires' => ($expires != 0)?TimestampHelper::toDatetime($expires):NULL,
            'scope' => $scope
        ]);
        if(!$model->save()){
            throw new \yii\base\Exception('Refresh Token not saved'.print_r($model->getErrors(), true));
        }
        return true;
    }

    public function unsetRefreshToken($refresh_token){
        return static::deleteAll(['refresh_token' => $refresh_token]);
    }
}