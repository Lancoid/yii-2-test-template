<?php

declare(strict_types=1);

namespace unit\modules\core\widgets\navBar;

use app\modules\core\widgets\navBar\NavBar;
use Codeception\Test\Unit;
use Yii;
use yii\i18n\PhpMessageSource;

/**
 * @internal
 *
 * @coversDefaultClass \app\modules\core\widgets\navBar\NavBar
 */
final class NavBarTest extends Unit
{
    protected function _before(): void
    {
        $navBar = new NavBar();
        $navBar->init();

        $messageSource = Yii::$app->i18n->getMessageSource('menu');

        $this->assertInstanceOf(
            PhpMessageSource::class,
            $messageSource,
            'Menu translation source should be PhpMessageSource');

        $this->assertSame(
            '@app/modules/core/messages',
            $messageSource->basePath,
            'Menu messages base path should be @app/modules/core/messages'
        );
    }

    public function testDefaultMenuTranslationSource(): void
    {
        Yii::$app->language = 'en-US';

        $this->assertSame('Login', NavBar::t('menu', 'Login'));
        $this->assertSame('Logout', NavBar::t('menu', 'Logout'));
    }

    public function testRussianMenuTranslationSource(): void
    {
        Yii::$app->language = 'ru-RU';

        $this->assertSame('Вход', NavBar::t('menu', 'Login'));
        $this->assertSame('Выход', NavBar::t('menu', 'Logout'));
    }
}
