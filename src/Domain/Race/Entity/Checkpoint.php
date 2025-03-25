<?php

namespace App\Domain\Race\Entity;

abstract class Checkpoint
{
    private string $id;
    private string $name;
    private string $location;
    private MetricsFromStart $metricsFromStart;
    private Race $race;

    public function __construct(
        string $id,
        string $name,
        string $location,
        MetricsFromStart $metricsFromStart,
        Race $race,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
        $this->metricsFromStart = $metricsFromStart;
        $this->race = $race;
    }

    abstract public function getCheckpointType(): CheckpointType;

    abstract public function validate(): void;

    protected function setMetricsFromStart(MetricsFromStart $metricsFromStart): void
    {
        $this->metricsFromStart = $metricsFromStart;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function setRace(Race $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getMetricsFromStart(): MetricsFromStart
    {
        return $this->metricsFromStart;
    }

    public function getRace(): Race
    {
        return $this->race;
    }
}
