<?php

use yii2mod\rbac\models\AssignmentModel;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\DetailView;
use yii2mod\rbac\RbacAsset;

RbacAsset::register($this);

$scopes = (new AssignmentModel($model->user))->getItems();

/* @var $this yii\web\View */
/* @var $model api\models\App */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Apps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="app-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'owner_id',
            'name',
            'private_key',
            'public_key',
            'trusted',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
        ],
    ]) ?>
    <div>
        <?=$this->render('@yii2mod/rbac/views/_dualListBox',[
            'opts' => Json::htmlEncode([
                'items' => $scopes,
            ]),
            'assignUrl' => ['assign', 'id' => $model->user->id],
            'removeUrl' => ['remove', 'id' => $model->user->id],
        ])?>
    </div>
</div>
