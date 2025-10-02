<?php

declare(strict_types=1);

namespace app\modules\track\repositories;

use app\modules\track\dataProviders\dto\TrackDataProviderListViewDto;
use app\modules\track\dataProviders\input\TrackSearchInputInterface;
use app\modules\track\services\dto\TrackDto;

interface TrackRepositoryInterface
{
    public function save(TrackDto $trackDto): int;

    public function delete(int $id): bool;

    public function findById(int $id): TrackDto;

    public function existById(int $id): bool;

    public function findByNumber(string $number, ?int $notId = null): TrackDto;

    public function existByNumber(string $number, ?int $notId = null): bool;

    public function findForListView(TrackSearchInputInterface $trackSearchInput): TrackDataProviderListViewDto;
}
