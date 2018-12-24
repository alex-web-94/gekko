<?php

namespace frontend\controllers;

use common\models\Transaction;
use frontend\models\TransactionSearch;
use Yii;
use yii\web\Controller;
use yii\web\JsExpression;


class ChartReportController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new TransactionSearch();
        $query = $searchModel->search(Yii::$app->request->bodyParams);
        $transactions = $query->all();

        $balance = 0;
        if (count($transactions) > 0) {
            $balance = TransactionSearch::getLastBalance($transactions[0], $searchModel->close_time_min);
        }

        $data = array_map(function (Transaction $transaction) use (&$balance) {
            if ($transaction->type == Transaction::TYPE_BALANCE) {
                $balance = $transaction->profit;
            } else if ($transaction->type == Transaction::TYPE_BUY) {
                $balance += $transaction->profit;
            }
            if (!in_array($transaction->close_time, [0, null, ''])) {
                return [
                    'x' => new JsExpression('new Date(' . strtotime($transaction->close_time) * 1000 . ')'),
                    'y' => $balance
                ];
            }
        }, $transactions);
        $data = array_diff($data, array(0, null, ''));

        return $this->render('index', [
            'data' => $data,
            'searchModel' => $searchModel
        ]);
    }

}
