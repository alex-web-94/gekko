<?php
/**
 * @var yii\web\View $this
 * @var \frontend\models\TransactionSearch $model
 * @var yii\widgets\ActiveForm $form
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

?>

<div class="report-file-search">

    <?php $form = ActiveForm::begin([
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    <div class="row">
        <div class="col-xs-6 col-sm-3">
            <?= $form->field($model, 'close_time_min')->widget(DateTimePicker::class, [
                'language' => 'ru',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy HH:ii',
                    'todayBtn' => true,
                ],
                'options' => [
                    'value' => Yii::$app->formatter->asDatetime($model->close_time_min, 'php:d.m.Y H:i')
                ]
            ])->label('От') ?>
        </div>
        <div class="col-xs-6 col-sm-3">
            <?= $form->field($model, 'close_time_max')->widget(DateTimePicker::class, [
                'language' => 'ru',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy HH:ii',
                    'todayBtn' => true,
                ],
                'options' => [
                    'value' => Yii::$app->formatter->asDatetime($model->close_time_max, 'php:d.m.Y H:i')
                ]
            ])->label('До') ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сбросить', ['/chart-report/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
