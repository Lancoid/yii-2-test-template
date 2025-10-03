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
 * @coversDefaultClass \app\modules\track\controllers\ApiController
 */
class ApiControllerCreateActionCest
{
    private const string METHOD_URL = '/track/api/create';

    public ?string $token = null;

    #[Before]
    public function prepareData(FunctionalTester $functionalTester): void
    {
        $functionalTester->loadLocalFixtures();

        $user = $functionalTester->grabRecord(User::class, ['id' => 1]);

        $functionalTester->assertInstanceOf(User::class, $user);
        $functionalTester->assertNotNull($user->access_token);

        $this->token = $user->access_token;
    }

    /**
     * Ensure successful creation returns expected result structure.
     */
    public function testActionCreateSuccess(FunctionalTester $functionalTester): void
    {
        $data = [
            'number' => 'TR-12345',
            'status' => TrackDictionary::STATUS_IN_PROGRESS,
        ];

        $functionalTester->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
        $functionalTester->sendPost(self::METHOD_URL, $data);
        $functionalTester->seeResponseCodeIs(HttpCodeDictionary::OK);

        $response = json_decode($functionalTester->grabResponse(), true);

        $functionalTester->assertArrayHasKey('result', $response);
        $functionalTester->assertArrayHasKey('id', $response['result']);
        $functionalTester->assertEquals(2, $response['result']['id']);

        $functionalTester->seeRecord(Track::class, [
            'id' => 2,
            'number' => 'TR-12345',
            'status' => TrackDictionary::STATUS_IN_PROGRESS,
        ]);
    }

    #[DataProvider('validationDataProvider')]
    public function testActionCreateValidation(FunctionalTester $functionalTester, Example $example): void
    {
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
            function () use ($functionalTester, $data) {
                $functionalTester->sendPost(self::METHOD_URL, $data);
            });
    }

    protected function validationDataProvider(): array
    {
        return [
            [
                'number' => '',
                'status' => '',
                'expectedAttribute' => 'number',
                'expectedMessage' => 'Необходимо заполнить «Номер».',
            ],
            [
                'number' => 'TR-12345',
                'status' => '',
                'expectedAttribute' => 'status',
                'expectedMessage' => 'Необходимо заполнить «Статус».',
            ],
            [
                'number' => '',
                'status' => TrackDictionary::STATUS_IN_PROGRESS,
                'expectedAttribute' => 'number',
                'expectedMessage' => 'Необходимо заполнить «Номер».',
            ],
        ];
    }
}
