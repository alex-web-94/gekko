<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ReportFile */

$this->title = 'Добавить файл сделки';
$this->params['breadcrumbs'][] = ['label' => 'Файлы сделок', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-file-create panel panel-default">

    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="panel-body">

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>
