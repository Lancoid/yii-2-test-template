<?php

/** @noinspection PhpGetterAndSetterCanBeReplacedWithPropertyHooksInspection */

declare(strict_types=1);

namespace app\modules\track\forms;

use app\modules\track\dictionaries\TrackDictionary;
use app\modules\track\services\create\input\TrackCreateInputInterface;
use app\modules\track\services\dto\TrackDto;
use app\modules\track\TrackModule;
use yii\base\Model;

class TrackCreateForm extends Model implements TrackCreateInputInterface
{
    public ?int $id = null;
    public ?string $number = null;
    public ?string $status = null;

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
            [['number', 'status'], 'trim'],

            [['number', 'status'], 'required'],

            [['number'], 'string', 'max' => 64],
            [['status'], 'string', 'max' => 64],

            [['status'], 'in', 'range' => TrackDictionary::getStatusList()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return [
            'number' => TrackModule::t('crud_form', 'number'),
            'status' => TrackModule::t('crud_form', 'status'),
            'choose_status' => TrackModule::t('crud_form', 'choose_status'),
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
}
