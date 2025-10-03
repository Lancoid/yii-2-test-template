<?php

declare(strict_types=1);

namespace app\modules\core\components\assetManager;

use RuntimeException;
use yii\web\AssetManager;

class AssetManagerReadOnly extends AssetManager
{
    public const string ASSETS_FILE = '/assets/assets.php';

    protected function publishDirectory($src, $options): array
    {
        $directory = $this->hash($src);
        $destinationDirectory = $this->basePath . DIRECTORY_SEPARATOR . $directory;

        if (!empty($options['forceCopy']) || ($this->forceCopy && !isset($options['forceCopy'])) || !is_dir($destinationDirectory)) {
            throw $this->directoryException($src);
        }

        /* @phpstan-ignore-next-line */
        if ($this->linkAssets && !is_dir($destinationDirectory)) {
            throw $this->directoryException($src);
        }

        return [
            $destinationDirectory,
            $this->baseUrl . '/' . $directory,
        ];
    }

    protected function publishFile($src): array
    {
        $directory = $this->hash($src);
        $fileName = basename($src);
        $destinationDirectory = $this->basePath . DIRECTORY_SEPARATOR . $directory;
        $destinationFile = $destinationDirectory . DIRECTORY_SEPARATOR . $fileName;

        if (!is_dir($destinationDirectory)) {
            throw $this->fileException($src);
        }

        if ($this->linkAssets) {
            throw $this->fileException($src);
        }

        if (@filemtime($destinationFile) < @filemtime($src)) {
            throw $this->fileException($src);
        }

        return [
            $destinationFile,
            $this->baseUrl . sprintf('/%s/%s', $directory, $fileName),
        ];
    }

    private function fileException(string $src): RuntimeException
    {
        return new RuntimeException(sprintf("You should publish file '%s' via ", $src) . self::ASSETS_FILE . ' file');
    }

    private function directoryException(string $src): RuntimeException
    {
        return new RuntimeException(sprintf("You should publish directory '%s' via ", $src) . self::ASSETS_FILE . ' file');
    }
}
