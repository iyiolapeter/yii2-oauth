<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel pso\yii2\oauth\models\search\OauthClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Oauth Clients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oauth-client-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Oauth Client', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'description',
            'logo',
            'client_id',
            'client_secret',
            //'auth_user_id',
            //'user_id',
            //'grant_types:ntext',
            //'redirect_uri:ntext',
            //'trusted',
            //'created_by',
            //'updated_by',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
