<?php

declare(strict_types=1);

namespace app\modules\track\services\dto;

class TrackDto
{
    private ?int $id = null;
    private ?int $createdAt = null;
    private ?int $updatedAt = null;
    private ?string $number = null;
    private ?string $status = null;

    public function setId(?int $id): TrackDto
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setCreatedAt(?int $createdAt): TrackDto
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(?int $updatedAt): TrackDto
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt;
    }

    public function setNumber(?string $number): TrackDto
    {
        $this->number = $number;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setStatus(?string $status): TrackDto
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return get_object_vars($this);
    }
}
