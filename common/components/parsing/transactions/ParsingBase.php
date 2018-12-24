<?php
namespace common\components\parsing\transactions;


use yii\base\BaseObject;

abstract class ParsingBase extends BaseObject
{
    public $path;

    abstract public function run();

    public function setPath($path)
    {
        $this->path = $path;
    }
}
