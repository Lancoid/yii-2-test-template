<?php

declare(strict_types=1);

namespace app\modules\track\services\delete;

use app\modules\track\repositories\TrackRepositoryInterface;

readonly class TrackDeleteService implements TrackDeleteServiceInterface
{
    public function __construct(
        private TrackRepositoryInterface $trackRepository
    ) {}

    public function handle(int $id): bool
    {
        return $this->trackRepository->delete($id);
    }
}
