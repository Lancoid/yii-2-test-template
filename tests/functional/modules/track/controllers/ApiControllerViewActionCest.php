<?php

declare(strict_types=1);

namespace app\tests\functional\modules\track\controllers;

use app\modules\core\dictionaries\HttpCodeDictionary;
use app\modules\track\dictionaries\TrackDictionary;
use app\modules\user\models\User;
use Codeception\Attribute\Before;
use FunctionalTester;

/**
 * @internal
 *
 * @coversDefaultClass \app\modules\track\controllers\ApiController::actionView
 */
class ApiControllerViewActionCest
{
    private const string METHOD_URL = '/track/api/view';

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
     * Ensure successful view returns expected result structure and data.
     */
    public function testActionViewSuccess(FunctionalTester $functionalTester): void
    {
        $id = 1;

        $functionalTester->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
        $functionalTester->sendGet(self::METHOD_URL . '?id=' . $id);
        $functionalTester->seeResponseCodeIs(HttpCodeDictionary::OK);

        $response = json_decode($functionalTester->grabResponse(), true);

        $functionalTester->assertArrayHasKey('result', $response);
        $functionalTester->assertArrayHasKey('data', $response['result']);

        $data = $response['result']['data'];

        $functionalTester->assertArrayHasKey('id', $data);
        $functionalTester->assertArrayHasKey('createdAt', $data);
        $functionalTester->assertArrayHasKey('updatedAt', $data);
        $functionalTester->assertArrayHasKey('number', $data);
        $functionalTester->assertArrayHasKey('status', $data);

        $functionalTester->assertEquals($id, $data['id']);
        $functionalTester->assertEquals('TRACK-1234567890', $data['number']);
        $functionalTester->assertEquals(TrackDictionary::STATUS_IN_PROGRESS, $data['status']);
    }

    /**
     * Ensure not found is returned for non-existing record.
     */
    public function testActionViewNotFound(FunctionalTester $functionalTester): void
    {
        $id = 999;

        $functionalTester->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
        $functionalTester->sendGet(self::METHOD_URL . '?id=' . $id);
        $functionalTester->seeResponseCodeIs(HttpCodeDictionary::NOT_FOUND);
    }
}
