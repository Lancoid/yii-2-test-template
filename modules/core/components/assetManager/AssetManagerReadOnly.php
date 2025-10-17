<?php

declare(strict_types=1);

namespace app\modules\core\components\assetManager;

use RuntimeException;
use yii\web\AssetManager;

/**
 * Read-only AssetManager for pre-published assets.
 *
 * This manager only allows access to assets that have already been published.
 * Any attempt to publish or link new assets will result in an exception.
 */
class AssetManagerReadOnly extends AssetManager
{
    /**
     * Path to the file containing pre-published assets.
     */
    public const ASSETS_FILE = '/assets/assets.php';

    /**
     * Returns the path and URL for an already published directory.
     *
     * @param string $src source directory path
     * @param array<string, mixed> $options publishing options
     *
     * @return array{0: string, 1: string} [destinationPath, publicUrl]
     *
     * @throws RuntimeException if publishing is forbidden or directory is missing
     */
    protected function publishDirectory($src, $options): array
    {
        if (!is_dir($src)) {
            throw new RuntimeException(sprintf("Source directory '%s' does not exist or is not a directory.", $src));
        }

        $directory = $this->hash($src);
        $destinationDirectory = $this->basePath . DIRECTORY_SEPARATOR . $directory;

        // Disallow force copy in read-only mode
        if (!empty($options['forceCopy']) || ($this->forceCopy && !isset($options['forceCopy']))) {
            throw $this->directoryException($src);
        }

        // Disallow linking assets in read-only manager
        if ($this->linkAssets) {
            throw $this->directoryException($src);
        }

        if (!is_dir($destinationDirectory)) {
            throw $this->directoryException($src);
        }

        $baseUrl = rtrim($this->baseUrl, '/');

        return [
            $destinationDirectory,
            $baseUrl . '/' . $directory,
        ];
    }

    /**
     * Returns the file path and URL for an already published file.
     *
     * @param string $src source file path
     *
     * @return array{0: string, 1: string} [destinationFile, publicUrl]
     *
     * @throws RuntimeException if file is not accessible or needs to be recreated
     */
    protected function publishFile($src): array
    {
        if (!is_file($src) || !is_readable($src)) {
            throw new RuntimeException(sprintf("Source file '%s' does not exist or is not readable.", $src));
        }

        $directory = $this->hash($src);
        $fileName = basename($src);

        $destinationDirectory = $this->basePath . DIRECTORY_SEPARATOR . $directory;
        $destinationFile = $destinationDirectory . DIRECTORY_SEPARATOR . $fileName;

        if (!is_dir($destinationDirectory)) {
            throw $this->fileException($src);
        }

        // Disallow linking assets in read-only manager
        if ($this->linkAssets) {
            throw $this->fileException($src);
        }

        if (!file_exists($destinationFile)) {
            throw $this->fileException($src);
        }

        $srcMtime = filemtime($src);
        $destMtime = filemtime($destinationFile);

        if (false === $srcMtime || false === $destMtime || $destMtime < $srcMtime) {
            throw $this->fileException($src);
        }

        $baseUrl = rtrim($this->baseUrl, '/');

        return [
            $destinationFile,
            $baseUrl . sprintf('/%s/%s', $directory, $fileName),
        ];
    }

    /**
     * Creates an exception for files with a clear message.
     *
     * @param string $src source file path
     */
    private function fileException(string $src): RuntimeException
    {
        return new RuntimeException(sprintf("You should publish file '%s' via %s file", $src, self::ASSETS_FILE));
    }

    /**
     * Creates an exception for directories with a clear message.
     *
     * @param string $src source directory path
     */
    private function directoryException(string $src): RuntimeException
    {
        return new RuntimeException(sprintf("You should publish directory '%s' via %s file", $src, self::ASSETS_FILE));
    }
}
