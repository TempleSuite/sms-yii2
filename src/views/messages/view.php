<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model maissoftware\sms\models\Messages */
?>
<div class="messages-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user.fullNameByLastName',
            'id',
            'user_id',
            'message',
            'sent_by',
            'sentByUser.fullNameByLastName',
            'sent_at',
        ],
    ]) ?>

</div>
