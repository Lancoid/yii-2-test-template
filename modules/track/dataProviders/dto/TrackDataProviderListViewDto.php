<?php

declare(strict_types=1);

namespace app\modules\track\dataProviders\dto;

use app\modules\track\services\dto\TrackDto;

/**
 * DTO used by data providers to expose list view of tracks with pagination data.
 */
class TrackDataProviderListViewDto
{
    private int $totalCount = 0;

    /**
     * @var array<int, TrackDto>
     */
    private array $data = [];

    /**
     * Set the total number of items to be paginated.
     *
     * @return $this
     */
    public function setTotalCount(int $totalCount): self
    {
        $this->totalCount = $totalCount;

        return $this;
    }

    /**
     * Get the total count of items for pagination.
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * Append DTO to the list.
     *
     * @return $this
     */
    public function setDto(TrackDto $trackDto): self
    {
        $this->data[] = $trackDto;

        return $this;
    }

    /**
     * Set DTO to the list by key (replace or insert).
     *
     * @return $this
     */
    public function addDto(int $key, TrackDto $trackDto): self
    {
        $this->data[$key] = $trackDto;

        return $this;
    }

    /**
     * Get list of track DTOs.
     *
     * @return array<int, TrackDto>
     */
    public function getData(): array
    {
        return $this->data;
    }
}
