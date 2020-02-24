<?php

namespace pso\yii2\oauth\controllers;

use Yii;
use pso\yii2\oauth\models\Scope;
use yii2mod\rbac\models\AuthItemModel;
use pso\yii2\oauth\models\search\ScopeSearch;
use yii2mod\rbac\controllers\PermissionController;

class ScopeController extends PermissionController
{
    public $searchClass = [
        'class' => ScopeSearch::class,
    ];

    protected $labels = [
        'Item' => 'Oauth Scope',
        'Items' => 'Oauth Scopes',
    ];
    /**
     * Creates a new AuthItem model.
     *
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Scope();
        $model->type = $this->type;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('yii2mod.rbac', 'Item has been saved.'));

            return $this->redirect(['view', 'id' => $model->name]);
        }

        return $this->render('create', ['model' => $model]);
    }

    protected function findModel(string $id): AuthItemModel
    {
        $model = parent::findModel($id);
        return new Scope($model->item);
    }
}