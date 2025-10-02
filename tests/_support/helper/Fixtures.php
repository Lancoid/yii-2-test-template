<?php

declare(strict_types=1);

namespace helper;

use Codeception\Exception\ModuleException;
use Codeception\Module;
use Codeception\Module\Db as DbModule;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\test\FixtureTrait;
use yii\test\InitDbFixture;

class Fixtures extends Module
{
    use FixtureTrait {
        loadFixtures as protected;
    }

    /**
     * @param array<string, mixed> $fixtures
     *
     * @throws InvalidConfigException
     * @throws ModuleException
     * @throws Exception
     */
    public function loadLocalFixtures(array $fixtures = []): void
    {
        /** @var array $defaultFixtures */
        $defaultFixtures = require Yii::getAlias('@tests/fixtures/default.php');

        $fixtures += $defaultFixtures;

        foreach ($fixtures as $className => &$fixture) {
            $tableName = Yii::createObject($className)?->getTableSchema()?->fullName;

            if ($tableName) {
                /** @var DbModule $db */
                $db = $this->getModule('Db');
                $db->_getDriver()->executeQuery('TRUNCATE TABLE ' . $tableName . ' RESTART IDENTITY;', []);
            }

            $compiled = [];

            if (is_numeric($className) && is_string($fixture)) {
                $className = $fixture;
            }

            if (is_string($fixture)) {
                $compiled['dataFile'] = $fixture;
            }

            if (is_array($fixture)) {
                $compiled['data'] = $fixture;
                $compiled['dataFile'] = false;
            }

            $compiled['class'] = $className;
            $fixture = $compiled;
        }

        unset($fixture);

        array_unshift($fixtures, InitDbFixture::class);

        $fixtureObjects = $this->createFixtures($fixtures);

        $this->loadFixtures($fixtureObjects);
    }
}
