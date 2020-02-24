<?php

namespace pso\yii2\oauth\controllers\actions;

use Yii;
use yii\base\Action;

class TestAction extends Action
{
    

    public function run(){
        return [
            'text' => 'Hey!'
        ];
    }
}