<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%transactions}}".
 *
 * @property int $id
 * @property int $ticket Номер транзакции
 * @property string $open_time Время открытия
 * @property int $type Тип транзакции
 * @property string $close_time Время закрытия
 * @property double $profit Прибыль
 */
class Transaction extends \yii\db\ActiveRecord
{
    const TYPE_BALANCE = 0;
    const TYPE_BUY = 1;
    const TYPE_BUY_STOP = 2;

    public static $typeLabels = [
        self::TYPE_BALANCE => 'balance',
        self::TYPE_BUY => 'buy',
        self::TYPE_BUY_STOP => 'buy stop',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%transactions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket', 'type'], 'integer'],
            ['ticket', function ($attribute) {
                if ($this[$attribute] <= 0) {
                    $this->addError($attribute, 'Поле ' . self::getAttributeLabel($attribute) . ' должно быть больше нуля');
                }
            }],
            [['open_time', 'close_time'], 'safe'],
            [['profit'], 'number'],
            [['ticket'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticket' => 'Номер транзакции',
            'open_time' => 'Время открытия',
            'type' => 'Тип транзакции',
            'close_time' => 'Время закрытия',
            'profit' => 'Прибыль',
        ];
    }

    /**
     * @return string
     */
    public function getTypeLabel()
    {
        return static::$typeLabels[$this->type];
    }


    /**
     * @param $transaction
     * @param string $close_time
     * @return float|int
     */
    public static function getLastBalance($transaction, $close_time)
    {
        $balance = 0;
        $firstTransactionBalance = Transaction::find()
            ->andWhere(['<=', 'close_time', $close_time])
            ->andWhere(['not in', 'id', $transaction->id])
            ->andWhere(['type' => Transaction::TYPE_BALANCE])
            ->orderBy(['close_time' => SORT_DESC, 'ticket' => SORT_DESC])
            ->one();
        if ($firstTransactionBalance) {
            $balance = $firstTransactionBalance->profit;
            $lastTransactions = Transaction::find()
                ->andWhere(['>=', 'close_time', $firstTransactionBalance->close_time])
                ->andWhere(['<=', 'close_time', $close_time])
                ->andWhere(['not in', 'id', $transaction->id])
                ->andWhere(['type' => Transaction::TYPE_BUY])
                ->orderBy(['close_time' => SORT_ASC])
                ->all();
            array_map(function (Transaction $transaction) use (&$balance) {
                $balance += $transaction->profit;
            }, $lastTransactions);
        }
        return $balance;
    }

    /**
     * {@inheritdoc}
     * @return TransactionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionsQuery(get_called_class());
    }
}
