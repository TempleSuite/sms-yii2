<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model maissoftware\sms\models\PhoneNumber */
?>
<div class="phone-number-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'entity_id',
            'phone_number',
            'extension',
            'table_name',
            'description',
        ],
    ]) ?>

</div>
