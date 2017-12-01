<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model maissoftware\sms\models\TwilioConfiguration */
?>
<div class="twilio-configuration-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'sid',
            'token',
            'notify_service_sid',
            'twilio_number',
        ],
    ]) ?>

</div>
