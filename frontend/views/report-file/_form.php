<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \frontend\models\ReportFileForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-file-form">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

            <?= $form->field($model, 'type')->dropDownList(\common\models\ReportFile::$typeLabels) ?>

            <?= $form->field($model, 'file')->fileInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
