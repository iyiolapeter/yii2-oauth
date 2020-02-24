<?php

use yii\helpers\Html;
use pso\yii2\widgets\ActiveForm;
use pso\yii2\widgets\AutoComplete;

/* @var $this yii\web\View */
/* @var $model pso\yii2\oauth\models\OauthClient */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oauth-client-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-7">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'user_id')->widget(AutoComplete::className(), [
                'url' => ['autocomplete', 'type' => 'owner'],
                'options' => [
                    'placeholder' => 'Search for User'
                ]
            ]) ?>

            <?= $form->field($model, 'grant_types')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'redirect_uri')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'trusted')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'logo')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
