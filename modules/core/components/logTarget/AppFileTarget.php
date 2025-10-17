<?php

declare(strict_types=1);

namespace app\modules\core\components\logTarget;

use Throwable;
use yii\helpers\VarDumper;
use yii\log\FileTarget;
use yii\log\Logger;

/**
 * Custom file log target for application logging.
 *
 * Formats log messages with timestamp, prefix, level, category, and message content.
 */
class AppFileTarget extends FileTarget
{
    /**
     * Formats a log message for output to a file.
     *
     * @param array{0: mixed, 1: int, 2: string, 3: float} $message log message data
     *
     * @return string formatted log message
     */
    public function formatMessage($message): string
    {
        [$text, $level, $category, $timestamp] = $message;

        $prefix = $this->getMessagePrefix($message);
        $levelName = Logger::getLevelName($level);

        $text = $this->convertText($text);

        return $this->getTime($timestamp) . " {$prefix}[{$levelName}][{$category}] \n{$text}";
    }

    /**
     * Converts log message content to string.
     *
     * @param mixed $text log message content
     *
     * @return string string representation of the message
     */
    private function convertText($text): string
    {
        if (is_string($text)) {
            return $text;
        }
        if ($text instanceof Throwable) {
            return (string)$text;
        }
        if (is_array($text)) {
            return json_encode($text, JSON_UNESCAPED_UNICODE) ?: '';
        }

        return VarDumper::export($text);
    }
}
