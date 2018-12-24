<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 22.12.2018
 * Time: 15:18
 */

namespace common\components\parsing\transactions;

use common\components\parsing\log\LoggerDBTransactions;
use common\components\parsing\log\LoggerModelBase;
use common\models\Transaction;
use PHPHtmlParser\Dom;
use Yii;

class HtmlTransactionsParsing extends ParsingBase
{
    public function run()
    {
        $dom = new Dom;
        $dom->loadFromFile($this->path);
        $trs = $dom->find('tr');
        foreach ($trs as $tr) {
            $tds = $tr->find('td');
            $type = $tds[$this->mapFieldsBuy['Type']]->innerHtml;
            $transactionModel = new Transaction();
            $transactionModel->ticket = (int)$tds[$this->mapFieldsBuy['Ticket']]->innerHtml;
            $transactionModel->open_time = $this->dateConvert($tds[$this->mapFieldsBuy['Open-Time']]->innerHtml);
            switch ($type) {
                case Transaction::$typeLabels[Transaction::TYPE_BALANCE]:
                    $transactionModel->type = Transaction::TYPE_BALANCE;
                    $transactionModel->close_time = $transactionModel->open_time;
                    $transactionModel->profit = (int)$tds[$this->mapFieldsBalance['Profit']]->innerHtml;
                    break;
                case Transaction::$typeLabels[Transaction::TYPE_BUY]:
                    $transactionModel->type = Transaction::TYPE_BUY;
                    $transactionModel->close_time = $this->dateConvert($tds[$this->mapFieldsBuy['Close-Time']]->innerHtml);
                    $transactionModel->profit = (int)$tds[$this->mapFieldsBuy['Profit']]->innerHtml;
                    break;
                case Transaction::$typeLabels[Transaction::TYPE_BUY_STOP]:
                    $transactionModel->type = Transaction::TYPE_BUY_STOP;
                    $transactionModel->close_time = $this->dateConvert($tds[$this->mapFieldsBuyStop['Close-Time']]->innerHtml);
                    $transactionModel->profit = (int)$tds[$this->mapFieldsBuyStop['Profit']]->innerHtml;
                    break;
            }
            $transactionModel->save();
        }
    }

    private function dateConvert($date)
    {
        preg_match('/(\d+)\.(\d+)\.(\d+)\s(\d+):(\d+):(\d+)/', $date, $m);
        return date('Y-m-d H:i:s', mktime($m[4], $m[5], $m[6], $m[2], $m[3], $m[1]));
    }

    private $mapFieldsBuy = [
        'Ticket' => 0,
        'Open-Time' => 1,
        'Type' => 2,
        'Size' => 3,
        'Item' => 4,
        'Price' => 5,
        'S/L' => 6,
        'T/P' => 7,
        'Close-Time' => 8,
        'Price-2' => 9,
        'Commission' => 10,
        'Taxes' => 11,
        'Swap' => 12,
        'Profit' => 13,
    ];
    private $mapFieldsBuyStop = [
        'Ticket' => 0,
        'Open-Time' => 1,
        'Type' => 2,
        'Size' => 3,
        'Item' => 4,
        'Price' => 5,
        'S/L' => 6,
        'T/P' => 7,
        'Close-Time' => 8,
        'Price-2' => 9,
        'Profit' => 10,
    ];
    private $mapFieldsBalance = [
        'Ticket' => 0,
        'Open-Time' => 1,
        'Type' => 2,
        'Size' => 3,
        'Profit' => 4,
    ];
}