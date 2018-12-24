<?php

namespace frontend\models;

use common\models\ReportFile;
use yii\helpers\ArrayHelper;


class ReportFileForm extends ReportFile
{
    public $file;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['type', 'file'], 'required'],
            ['file', 'file', 'extensions' => 'html, xlsx, xls, xml', 'maxSize' => 12 * 1024 * 1024]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'file' => 'Файл'
        ]);
    }

    public function upload(){

    }

}
