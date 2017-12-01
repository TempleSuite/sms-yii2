<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model maissoftware\sms\models\TwilioConfiguration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="twilio-configuration-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'notify_service_sid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'twilio_number')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
