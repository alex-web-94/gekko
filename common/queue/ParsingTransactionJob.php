<?php

namespace common\queue;

use common\components\parsing\transactions\ExcelTransactionsParsing;
use common\components\parsing\transactions\HtmlTransactionsParsing;
use common\components\parsing\transactions\ParsingBase;
use common\models\ReportFile;
use InvalidArgumentException;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class ParsingTransactionJob extends BaseObject implements JobInterface
{
    /** @var ReportFile */
    public $report_file;
    /** @var ParsingBase */
    private $parcink;

    private function createParcink()
    {
        switch ($this->report_file->type) {
            case ReportFile::TYPE_HTML:
                $this->parcink = new HtmlTransactionsParsing();
                break;
            case ReportFile::TYPE_EXCEL:
                $this->parcink = new ExcelTransactionsParsing();
                break;
            default:
                $this->parcink = new HtmlTransactionsParsing();
        }
        $this->parcink->setPath(Yii::getAlias('@secured/') . $this->report_file->getFullName());
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        if (is_null($this->report_file)) {
            throw new InvalidArgumentException('Param `report_file` is required.');
        }
        $this->createParcink();
    }

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $this->report_file->updateAttributes([
            'status' => ReportFile::STATUS_PROCESS
        ]);
        $this->parcink->run();
        $this->report_file->updateAttributes([
            'status' => ReportFile::STATUS_COMPLETED
        ]);
    }

}