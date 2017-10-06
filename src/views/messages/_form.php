<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
//use common\models\User;
use maissoftware\sms\models\User;

/* @var $this yii\web\View */
/* @var $model frontend\models\Messages */
/* @var $form yii\widgets\ActiveForm */

$user = new User();
$data = User::find()->all();
ArrayHelper::multisort($data, ['last_name', 'first_name'], [SORT_ASC, SORT_ASC]);

?>

<div class="messages-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--<?= $form->field($model, 'user_id')->textInput() ?> -->

    <?= $form->field($model, 'user_id')->widget(Select2::className(), [
            'data' => ArrayHelper::map($data, 'id', 'fullNameByLastName'),
            'options' => ['placeholder' => 'Select a user ...', "multiple" => true],
            'pluginOptions' => ['initialize' => true],
            ]
    )->label('Name');
    ?>

    <?= $form->field($model, 'message')->textInput(['maxlength' => true]) ?>

    <!--<?= $form->field($model, 'sent_at')->textInput() ?> -->
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
