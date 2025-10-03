<?php

declare(strict_types=1);

namespace app\tests\functional\modules\track\controllers;

use app\modules\core\dictionaries\HttpCodeDictionary;
use app\modules\core\services\exceptions\ServiceFormValidationException;
use app\modules\track\dictionaries\TrackDictionary;
use app\modules\track\models\Track;
use app\modules\user\models\User;
use Codeception\Attribute\Before;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use FunctionalTester;

/**
 * @internal
 *
 * @coversDefaultClass \app\modules\track\controllers\ApiController::actionUpdate
 */
class ApiControllerUpdateActionCest
{
    private const string METHOD_URL = '/track/api/update';

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
     * Ensure successful update returns expected result structure and updates DB.
     */
    public function testActionUpdateSuccess(FunctionalTester $functionalTester): void
    {
        $id = 1;

        $functionalTester->seeRecord(Track::class, ['id' => $id]);

        $data = [
            'number' => 'TR-UPDATED-12345',
            'status' => TrackDictionary::STATUS_COMPLETED,
        ];

        $functionalTester->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
        $functionalTester->sendPut(self::METHOD_URL . '?id=' . $id, $data);
        $functionalTester->seeResponseCodeIs(HttpCodeDictionary::OK);

        $response = json_decode($functionalTester->grabResponse(), true);
        $functionalTester->assertArrayHasKey('result', $response);
        $functionalTester->assertArrayHasKey('id', $response['result']);
        $functionalTester->assertEquals($id, $response['result']['id']);

        // Check that the DB record has been updated
        $functionalTester->seeRecord(Track::class, [
            'id' => $id,
            'number' => 'TR-UPDATED-12345',
            'status' => TrackDictionary::STATUS_COMPLETED,
        ]);
    }

    #[DataProvider('validationDataProvider')]
    public function testActionUpdateValidation(FunctionalTester $functionalTester, Example $example): void
    {
        $id = 1;

        $data = [
            'number' => $example['number'],
            'status' => $example['status'],
        ];

        $functionalTester->haveHttpHeader('Authorization', 'Bearer ' . $this->token);

        $functionalTester->expectThrowable(
            new ServiceFormValidationException(
                $example['expectedAttribute'],
                $example['expectedMessage'],
                HttpCodeDictionary::BAD_REQUEST
            ),
            function () use ($functionalTester, $id, $data): void {
                $functionalTester->sendPut(self::METHOD_URL . '?id=' . $id, $data);
            }
        );
    }

    protected function validationDataProvider(): array
    {
        return [
            [
                'number' => '',
                'status' => TrackDictionary::STATUS_IN_PROGRESS,
                'expectedAttribute' => 'number',
                'expectedMessage' => 'Необходимо заполнить «Номер».',
            ],
            [
                'number' => 'TR-UPDATED-12345',
                'status' => '',
                'expectedAttribute' => 'status',
                'expectedMessage' => 'Необходимо заполнить «Статус».',
            ],
            [
                'number' => 'TR-UPDATED-12345',
                'status' => 'invalid',
                'expectedAttribute' => 'status',
                'expectedMessage' => 'Значение «Статус» неверно.',
            ],
        ];
    }

    /**
     * Ensure not found is returned for non-existing record.
     */
    public function testActionUpdateNotFound(FunctionalTester $functionalTester): void
    {
        $id = 999;

        $data = [
            'number' => 'TR-NONEXIST-0001',
            'status' => TrackDictionary::STATUS_COMPLETED,
        ];

        $functionalTester->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
        $functionalTester->sendPut(self::METHOD_URL . '?id=' . $id, $data);
        $functionalTester->seeResponseCodeIs(HttpCodeDictionary::NOT_FOUND);
    }
}
