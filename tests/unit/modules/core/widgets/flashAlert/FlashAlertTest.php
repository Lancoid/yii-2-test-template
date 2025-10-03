<?php

declare(strict_types=1);

namespace app\tests\unit\modules\core\widgets\flashAlert;

use app\modules\core\widgets\flashAlert\FlashAlert;
use Codeception\Test\Unit;
use Yii;

/**
 * @internal
 *
 * @coversDefaultClass \app\modules\core\widgets\flashAlert\FlashAlert
 */
final class FlashAlertTest extends Unit
{
    protected function _before(): void
    {
        // Ensure the session is available and clean before each test
        $session = Yii::$app->session;

        if (!$session->isActive) {
            $session->open();
        }

        $session->removeAllFlashes();
    }

    public function testReturnsEmptyStringWhenNoFlashes(): void
    {
        $flashAlert = new FlashAlert();
        $output = $flashAlert->run();

        $this->assertSame('', $output, 'Widget should return empty string when there are no flashes');
    }

    public function testRendersKnownFlashTypes(): void
    {
        $session = Yii::$app->session;
        $session->addFlash('success', 'All good');
        $session->addFlash('info', 'FYI');
        $session->addFlash('warning', 'Be careful');
        $session->addFlash('error', 'Something went wrong');

        $output = (new FlashAlert())->run();

        // success
        $this->assertStringContainsString('<div class="alert alert-success">', $output);
        $this->assertStringContainsString('<i class="fa fa-check"></i>', $output);
        $this->assertStringContainsString('Успех', $output);
        $this->assertStringContainsString('<p>All good</p>', $output);

        // info
        $this->assertStringContainsString('<div class="alert alert-info">', $output);
        $this->assertStringContainsString('<i class="fa fa-info-circle"></i>', $output);
        $this->assertStringContainsString('Информация', $output);
        $this->assertStringContainsString('<p>FYI</p>', $output);

        // warning
        $this->assertStringContainsString('<div class="alert alert-warning">', $output);
        $this->assertStringContainsString('<i class="fa fa-warning"></i>', $output);
        $this->assertStringContainsString('Предупреждение', $output);
        $this->assertStringContainsString('<p>Be careful</p>', $output);

        // error
        $this->assertStringContainsString('<div class="alert alert-danger">', $output);
        $this->assertStringContainsString('<i class="fa fa-ban"></i>', $output);
        $this->assertStringContainsString('Ошибка', $output);
        $this->assertStringContainsString('<p>Something went wrong</p>', $output);
    }

    public function testUnknownFlashTypeFallsBackToInfoAsClass(): void
    {
        $session = Yii::$app->session;
        $session->addFlash('custom', 'Hello');

        $output = (new FlashAlert())->run();

        // Should use alert-alert-info class, header and icon
        $this->assertStringContainsString('<div class="alert alert-info">', $output);
        $this->assertStringContainsString('<i class="fa fa-info-circle"></i>', $output);
        $this->assertStringContainsString('Информация', $output);
        $this->assertStringContainsString('<p>Hello</p>', $output);
    }

    public function testMultipleMessagesAreRendered(): void
    {
        $session = Yii::$app->session;
        $session->addFlash('success', 'First');
        $session->addFlash('success', 'Second');

        $output = (new FlashAlert())->run();

        $this->assertStringContainsString('<div class="alert alert-success">', $output);
        $this->assertStringContainsString('<p>First</p>', $output);
        $this->assertStringContainsString('<p>Second</p>', $output);
    }
}
