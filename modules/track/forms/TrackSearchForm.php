<?php

/** @noinspection PhpGetterAndSetterCanBeReplacedWithPropertyHooksInspection */

declare(strict_types=1);

namespace app\modules\track\forms;

use app\modules\track\dataProviders\input\TrackSearchInputInterface;
use app\modules\track\dictionaries\TrackDictionary;
use app\modules\track\services\dto\TrackDto;
use app\modules\track\TrackModule;
use yii\base\Model;

class TrackSearchForm extends Model implements TrackSearchInputInterface
{
    public function formName(): string
    {
        return '';
    }

    public ?int $id = null;
    public ?string $number = null;
    public ?string $status = null;
    public int $page = 1;

    public function __construct(?TrackDto $trackDto = null)
    {
        parent::__construct();

        if ($trackDto instanceof TrackDto) {
            $this->id = $trackDto->getId();
            $this->number = $trackDto->getNumber();
            $this->status = $trackDto->getStatus();
        }
    }

    public function rules(): array
    {
        return [
            [
                ['number', 'status'],
                'filter',
                'filter' => function ($value): mixed {
                    if (!is_string($value)) {
                        return $value;
                    }

                    $value = trim($value);

                    if ('' === $value) {
                        return null;
                    }

                    return $value;
                },
            ],

            [['number'], 'string', 'max' => 64],
            [['status'], 'string', 'max' => 64],

            [['status'], 'in', 'range' => TrackDictionary::getStatusList()],

            ['page', 'default', 'value' => 1],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return [
            'id' => TrackModule::t('crud_form', 'id'),
            'created_at' => TrackModule::t('crud_form', 'created_at'),
            'updated_at' => TrackModule::t('crud_form', 'updated_at'),
            'number' => TrackModule::t('crud_form', 'number'),
            'status' => TrackModule::t('crud_form', 'status'),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}
