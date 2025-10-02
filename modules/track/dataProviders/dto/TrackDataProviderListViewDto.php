<?php

declare(strict_types=1);

namespace app\modules\track\dataProviders\dto;

use app\modules\track\services\dto\TrackDto;

class TrackDataProviderListViewDto
{
    private int $totalCount = 0;

    /**
     * @var TrackDto[]
     */
    private array $data = [];

    public function setTotalCount(int $totalCount): self
    {
        $this->totalCount = $totalCount;

        return $this;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function setDto(TrackDto $trackDto): self
    {
        $this->data[] = $trackDto;

        return $this;
    }

    public function addDto(int $key, TrackDto $trackDto): self
    {
        $this->data[$key] = $trackDto;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
