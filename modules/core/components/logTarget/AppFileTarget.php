<?php

declare(strict_types=1);

namespace app\modules\core\components\logTarget;

use Throwable;
use yii\helpers\VarDumper;
use yii\log\FileTarget;
use yii\log\Logger;

class AppFileTarget extends FileTarget
{
    /**
     * @param array $message
     */
    public function formatMessage($message): string
    {
        /**
         * @var mixed $text
         * @var int $level
         * @var string $category
         * @var float $timestamp
         */
        [$text, $level, $category, $timestamp] = $message;

        $prefix = $this->getMessagePrefix($message);

        $level = Logger::getLevelName($level);

        if (!is_string($text)) {
            if ($text instanceof Throwable) {
                $text = (string)$text;
            } elseif (is_array($text)) {
                $text = json_encode($text, JSON_UNESCAPED_UNICODE);
            } else {
                $text = VarDumper::export($text);
            }
        }

        return $this->getTime($timestamp) . " {$prefix}[{$level}][{$category}] \n{$text}";
    }
}
