<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%report_files}}".
 *
 * @property int $id
 * @property string $path
 * @property string $name
 * @property string $ext
 * @property int $type
 * @property int $status
 * @property string $original_name
 * @property string $created_at
 */
class ReportFile extends \yii\db\ActiveRecord
{
    const TYPE_HTML = 0;
    const TYPE_EXCEL = 1;

    const STATUS_NEW = 0;
    const STATUS_PROCESS = 1;
    const STATUS_COMPLETED = 2;

    public static $statusLabels = [
        self::STATUS_NEW => 'Необработанный',
        self::STATUS_PROCESS => 'В процессе',
        self::STATUS_COMPLETED => 'Обработан',
    ];

    public static $typeLabels = [
        self::TYPE_HTML => 'Html',
        self::TYPE_EXCEL => 'Excel',
    ];

    /**
     * @return string
     */
    public function getStatusLabel()
    {
        return static::$statusLabels[$this->status];
    }

    /**
     * @return string
     */
    public function getTypeLabel()
    {
        return static::$typeLabels[$this->type];
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->path . DIRECTORY_SEPARATOR . $this->name . '.' . $this->ext;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%report_files}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status'], 'integer'],
            [['path', 'name', 'ext'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Путь',
            'name' => 'Имя',
            'ext' => 'Расширение',
            'type' => 'Тип файла',
            'status' => 'Статус',
            'typeLabel' => 'Тип файла',
            'statusLabel' => 'Статус',
            'original_name' => 'Имя',
            'created_at' => 'Дата создания',
        ];
    }

    public function beforeSave($insert)
    {
        $this->created_at = Yii::$app->formatter->asDatetime(time(), 'php:Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     * @return ReportFileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReportFileQuery(get_called_class());
    }

    public function afterDelete()
    {
        $path = Yii::getAlias('@secured/') . $this->getFullName();
        if (file_exists($path)) {
            @unlink($path);
        }
        parent::afterDelete();
    }
}
