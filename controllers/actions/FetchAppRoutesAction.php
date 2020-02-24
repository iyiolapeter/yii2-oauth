<?php

namespace pso\yii2\oauth\controllers\actions;

use Yii;
use yii\base\Action;
use pso\yii2\oauth\models\Endpoint;

class FetchAppRoutesAction extends Action
{
    public $excludeModules = [];

    public function run(){
        $model = new Endpoint([
            'excludeModules' => $this->excludeModules
        ]);
        return $model->getAvailableAndAssignedRoutes();
    }
}