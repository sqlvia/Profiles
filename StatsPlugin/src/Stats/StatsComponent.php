<?php

declare(strict_types=1);

namespace Stats;

use ProfileSystem\component\ProfileComponent;

class StatsComponent implements ProfileComponent {

    public function __construct(
        private int $kills = 0,
        private int $deaths = 0
    ) {}

    public function getName(): string {
        return "stats";
    }

    public function getStorageTarget(): string {
        return "remote"; // MySQL
    }

    public function getKills(): int {
        return $this->kills;
    }

    public function addKill(): void {
        $this->kills++;
    }

    public function getDeaths(): int {
        return $this->deaths;
    }

    public function addDeath(): void {
        $this->deaths++;
    }

    public function serialize(): array {
        return [
            "kills" => $this->kills,
            "deaths" => $this->deaths
        ];
    }

    public function deserialize(array $data): void {
        $this->kills = $data["kills"] ?? 0;
        $this->deaths = $data["deaths"] ?? 0;
    }
}
