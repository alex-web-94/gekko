<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ReportFileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Импорт файлов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-file-index panel panel-default">
    <?php Pjax::begin(); ?>
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <div class="pull-right">
            <p>
                <?= Html::a('Загрузить файл сделки', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
    </div>
    <div class="panel-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                'original_name',
                'ext',
                'typeLabel',
                'statusLabel',
                'created_at',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'visibleButtons' => ['update' => false]
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
