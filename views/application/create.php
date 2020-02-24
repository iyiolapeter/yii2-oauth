<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model pso\yii2\oauth\models\OauthClient */

$this->title = 'Create Oauth Client';
$this->params['breadcrumbs'][] = ['label' => 'Oauth Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oauth-client-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
