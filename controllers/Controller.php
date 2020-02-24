<?php

namespace pso\yii2\oauth\controllers;

use yii\web\Controller as BaseController;
use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

class Controller extends BaseController
{
    public static function getErrors(array $models) {
        $result = [];
        // The code below comes from ActiveForm::validate(). We do not need to validate the model
        // again, as it was already validated by save(). Just collect the messages.
        foreach($models as $model){
            foreach ($model->getErrors() as $attribute => $errors) {
                $result[Html::getInputId($model, $attribute)] = $errors;
            }
        }
        return ['validation' => $result];
    }

    protected function getIsAjaxRequest(){
        return Yii::$app->request->isAjax;
    }
    protected function renderAlt($view, $params = [])
    {
        if($this->isAjaxRequest){
            return $this->renderAjax($view, $params);
        }
        return $this->render($view, $params);
    }

    protected function flash($type, $message){
        $this->session->setFlash($type, $message);
        return $this;
    }

    protected function getSession(){
        return Yii::$app->session;
    }

    protected function getUser(){
        return Yii::$app->user->identity;
    }

    protected function redirectAlt($url, array $flash = NULL){
        $success = true;
        $message = NULL;
        if(!is_null($flash) && count($flash) > 1){
            if($this->isAjaxRequest){
                $success = $flash[0] === "success";
                $message = $flash[1];
            } else {
                $this->flash($flash[0], $flash[1]);
            }
        }
        if($this->isAjaxRequest){
            return $this->asJson([
                'success' => $success,
                'message' => $message,
                'redirectUrl' => Url::to($url)
            ]);
        }
        return $this->redirect($url);
    }

    protected function sendAjaxValidation(array $models)
    {
        $response = static::getErrors($models);
        $response['success'] = false;
        $response['context'] = 'danger';
        $response['message'] = 'Form validation failed';
        return $this->asJson($response);
    }

    protected function sendAjaxResponse($success, $message, $redirect = NULL){
        return $this->asJson([
            'success' => $success,
            'message' => $message,
            'redirectUrl' => $redirect
        ]);
    }
}