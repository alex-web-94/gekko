<?php

/**
 * @var \yii\web\View $this
 * @var array $data
 * @var \frontend\models\TransactionSearch $searchModel
 */

use phpnt\chartJS\ChartJs;
use yii\helpers\Html;

$this->title = 'График роста баланса';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('http://momentjs.com/downloads/moment.js');
?>

<div class="report-file-index panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php
        echo $this->render('_search', ['model' => $searchModel]);
        ?>
    </div>
    <div class="panel-body">
        <?php
        // определение данных
        $dataWeatherOne = [
            'datasets' => [
                [
                    'data' => $data,
                    'label' => $this->title . ' от ' .
                        Yii::$app->formatter->asDatetime($searchModel->close_time_min, 'php:d.m.Y H:i') . ' до ' .
                        Yii::$app->formatter->asDatetime($searchModel->close_time_max, 'php:d.m.Y H:i'),
                    'fill' => false,
                    'lineTension' => 0.1,
                    'backgroundColor' => "rgba(75,192,192,0.4)",
                    'borderColor' => "rgba(75,192,192,1)",
                    'borderCapStyle' => 'butt',
                    'borderDash' => [],
                    'borderDashOffset' => 0.0,
                    'borderJoinStyle' => 'miter',
                    'pointBorderColor' => "rgba(75,192,192,1)",
                    'pointBackgroundColor' => "#fff",
                    'pointBorderWidth' => 1,
                    'pointHoverRadius' => 5,
                    'pointHoverBackgroundColor' => "rgba(75,192,192,1)",
                    'pointHoverBorderColor' => "rgba(220,220,220,1)",
                    'pointHoverBorderWidth' => 2,
                    'pointRadius' => 1,
                    'pointHitRadius' => 10,
                    'spanGaps' => false,
                ]
            ]
        ];
        // вывод графиков
        echo ChartJs::widget([
            'type' => ChartJs::TYPE_LINE,
            'data' => $dataWeatherOne,
            'options' => [
                'scales' => [
                    'xAxes' => [[
                        'type' => 'time',
                        'ticks' => ['source' => 'data'],
                        'time' => [
                            'unit' => 'month',
                            'displayFormats' => [
                                'month' => 'DD.MM.YYYY HH:mm'
                            ],
                            'tooltipFormat' => 'DD.MM.YYYY HH:mm'
                        ],
                    ]]
                ]
            ]
        ]);
        ?>
    </div>
</div>
