<?php

declare(strict_types=1);

namespace app\modules\core\components\assetManager;

use RuntimeException;
use yii\web\AssetManager;

class AssetManagerReadOnly extends AssetManager
{
    /**
     * Путь к файлу, содержащему предзапубликованные ассеты.
     */
    public const string ASSETS_FILE = '/assets/assets.php';

    /**
     * Возвращает путь и URL для уже опубликованной директории.
     *
     * @param string $src Источник директории
     * @param array<string,mixed> $options Опции публикации
     *
     * @return array{0:string,1:string} [destinationPath, publicUrl]
     *
     * @throws RuntimeException Если публикация запрещена или директория отсутствует
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
     * Возвращает файл и URL для уже опубликованного файла.
     *
     * @param string $src Путь к исходному файлу
     *
     * @return array{0:string,1:string} [destinationFile, publicUrl]
     *
     * @throws RuntimeException Если файл недоступен или нужно пересоздать
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
     * Формирует исключение для файлов с понятным сообщением.
     */
    private function fileException(string $src): RuntimeException
    {
        return new RuntimeException(sprintf("You should publish file '%s' via %s file", $src, self::ASSETS_FILE));
    }

    /**
     * Формирует исключение для директорий.
     */
    private function directoryException(string $src): RuntimeException
    {
        return new RuntimeException(sprintf("You should publish directory '%s' via %s file", $src, self::ASSETS_FILE));
    }
}
