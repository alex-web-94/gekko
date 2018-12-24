<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[ReportFile]].
 *
 * @see ReportFile
 */
class ReportFileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ReportFile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ReportFile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
