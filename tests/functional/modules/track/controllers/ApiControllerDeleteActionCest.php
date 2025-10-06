<?php

declare(strict_types=1);

namespace app\tests\functional\modules\track\controllers;

use app\modules\core\dictionaries\HttpCodeDictionary;
use app\modules\track\models\Track;
use app\modules\user\models\User;
use Codeception\Attribute\Before;
use FunctionalTester;

/**
 * @internal
 *
 * @coversDefaultClass \app\modules\track\controllers\ApiController::actionDelete
 */
class ApiControllerDeleteActionCest
{
    private const string METHOD_URL = '/track/api/delete';

    public ?string $token = null;

    #[Before]
    public function prepareData(FunctionalTester $functionalTester): void
    {
        $functionalTester->loadLocalFixtures();

        /** @var User $user */
        $user = $functionalTester->grabRecord(User::class, ['id' => 1]);

        $functionalTester->assertInstanceOf(User::class, $user);
        $functionalTester->assertNotNull($user->access_token);

        $this->token = $user->access_token;
    }

    /**
     * Ensure successful delete returns expected result structure.
     */
    public function testActionDeleteSuccess(FunctionalTester $functionalTester): void
    {
        $id = 1;

        $functionalTester->seeRecord(Track::class, ['id' => $id]);

        $functionalTester->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
        $functionalTester->sendDelete(self::METHOD_URL . '?id=' . $id);
        $functionalTester->seeResponseCodeIs(HttpCodeDictionary::OK);

        $deleteResponse = json_decode($functionalTester->grabResponse(), true);
        $functionalTester->assertArrayHasKey('result', $deleteResponse);
        $functionalTester->assertArrayHasKey('is_deleted', $deleteResponse['result']);
        $functionalTester->assertTrue((bool)$deleteResponse['result']['is_deleted']);

        $functionalTester->dontSeeRecord(Track::class, ['id' => $id]);
    }

    /**
     * Ensure not found is returned for non-existing record.
     */
    public function testActionDeleteNotFound(FunctionalTester $functionalTester): void
    {
        $id = 1;

        $functionalTester->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
        $functionalTester->sendDelete(self::METHOD_URL . '?id=' . $id);
        $functionalTester->seeResponseCodeIs(HttpCodeDictionary::NOT_FOUND);
    }
}
