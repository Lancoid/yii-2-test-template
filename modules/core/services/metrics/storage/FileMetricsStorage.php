<?php

declare(strict_types=1);

namespace app\modules\core\services\metrics\storage;

use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;

class FileMetricsStorage implements MetricsStorageInterface
{
    private string $storagePath;

    /**
     * @var array<int|string, mixed>
     */
    private array $buffer = [];
    private int $bufferSize = 100;

    /**
     * @throws Exception
     */
    public function __construct(string $storagePath = '@runtime/metrics')
    {
        /* @phpstan-ignore-next-line */
        $this->storagePath = Yii::getAlias($storagePath);
        FileHelper::createDirectory($this->storagePath);
    }

    public function store(string $type, array $data): void
    {
        $data['type'] = $type;
        $data['timestamp'] = $data['timestamp'] ?? time();

        $this->buffer[] = $data;

        if (count($this->buffer) >= $this->bufferSize) {
            $this->flushBuffer();
        }
    }

    public function storeBatch(array $metrics): void
    {
        foreach ($metrics as $metric) {
            $this->buffer[] = $metric;
        }

        if (count($this->buffer) >= $this->bufferSize) {
            $this->flushBuffer();
        }
    }

    public function retrieve(int $fromTimestamp, ?int $toTimestamp = null, ?string $type = null): array
    {
        $toTimestamp = $toTimestamp ?? time();
        $metrics = [];

        $files = glob($this->storagePath . '/metrics-*.json');

        foreach ($files ?: [] as $file) {
            $content = file_get_contents($file);
            $lines = explode("\n", trim($content ?: ''));

            foreach ($lines as $line) {
                if (empty($line)) {
                    continue;
                }

                $metric = json_decode($line, true);
                if (!$metric) {
                    continue;
                }
                if (!isset($metric['timestamp'])) {
                    continue;
                }

                if ($metric['timestamp'] >= $fromTimestamp && $metric['timestamp'] <= $toTimestamp && (null === $type || ($metric['type'] ?? null) === $type)) {
                    $metrics[] = $metric;
                }
            }
        }

        return $metrics;
    }

    public function cleanup(int $olderThanTimestamp): int
    {
        $deleted = 0;
        $files = glob($this->storagePath . '/metrics-*.json');

        foreach ($files ?: [] as $file) {
            // Extract date from filename: metrics-2024-01-15.json
            $filename = basename($file);
            if (preg_match('/metrics-(\d{4}-\d{2}-\d{2})\.json/', $filename, $matches)) {
                $fileTimestamp = strtotime($matches[1]);
                if ($fileTimestamp < $olderThanTimestamp && unlink($file)) {
                    ++$deleted;
                }
            }
        }

        return $deleted;
    }

    private function flushBuffer(): void
    {
        if (empty($this->buffer)) {
            return;
        }

        $date = date('Y-m-d');
        $filename = $this->storagePath . sprintf('/metrics-%s.json', $date);

        $lines = array_map(function ($metric) {
            return json_encode($metric);
        }, $this->buffer);

        file_put_contents($filename, implode("\n", $lines) . "\n", FILE_APPEND | LOCK_EX);

        $this->buffer = [];
    }

    public function __destruct()
    {
        $this->flushBuffer();
    }
}
