<?php

declare(strict_types=1);

namespace app\modules\track\dataProviders;

use app\modules\track\dataProviders\dto\TrackDataProviderListViewDto;
use app\modules\track\dataProviders\input\TrackSearchInputInterface;
use app\modules\track\services\dto\TrackDto;

interface TrackDataProviderInterface
{
    public function getOne(int $id): ?TrackDto;

    public function getListView(TrackSearchInputInterface $trackSearchInput): TrackDataProviderListViewDto;
}
