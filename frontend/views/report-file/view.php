<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ReportFile */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Файлы сделок', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="report-file-view panel panel-default">

    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="pull-right">
            <p>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="panel-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'original_name',
                'ext',
                'typeLabel',
                'statusLabel',
                'created_at',
            ],
        ]) ?>
    </div>

</div>
