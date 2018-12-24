<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;
use common\models\Transaction;


class TransactionSearch extends Transaction
{
    public $close_time_min;
    public $close_time_max;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['close_time_min', 'close_time_max'], 'filter', 'filter' => function ($value) {
                return date('Y-m-d H:i:s', strtotime($value));
            }],
            [['close_time_min', 'close_time_max'], 'datetime', 'format' => 'php:Y-m-d H:i:s']
        ];
    }

    /**
     * @param $params
     * @return \common\models\TransactionsQuery
     */
    public function search($params)
    {
        $query = Transaction::find()
            ->andWhere(['in', 'type', [Transaction::TYPE_BUY, Transaction::TYPE_BALANCE]])
            ->orderBy(['close_time' => SORT_ASC, 'ticket' => SORT_ASC]);

        $maxDate = static::find()->max('close_time');
        $this->close_time_min = date('Y-m-d H:i:s', strtotime($maxDate) - 3600 * 24 * 30 * 12);
        $this->close_time_max = $maxDate;
        $this->load($params);

        if (!$this->validate()) {
            $query->andWhere(['AND',
                ['>=', 'close_time', date('Y-m-d H:i:s', time() - 3600 * 24 * 90)],
                ['<=', 'close_time', date('Y-m-d H:i:s')]
            ]);
            return $query;
        }

        $query->andWhere(['AND',
            ['>=', 'close_time', $this->close_time_min],
            ['<=', 'close_time', $this->close_time_max]
        ]);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'close_time_min' => 'Время открытия',
            'close_time_max' => 'Время открытия',
        ];
    }
}
