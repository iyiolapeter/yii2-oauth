<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model pso\yii2\oauth\models\OauthClient */

$this->title = 'Update Oauth Client: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Oauth Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->client_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="oauth-client-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
