<?php

declare(strict_types=1);

namespace ProfileSystem\component;

class StatsComponent implements ProfileComponent {

    public function __construct(
        private int $kills = 0,
        private int $deaths = 0,
        private string $rank = "Guest"
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

    public function getRank(): string {
        return $this->rank;
    }

    public function setRank(string $rank): void {
        $this->rank = $rank;
    }

    public function serialize(): array {
        return [
            "kills" => $this->kills,
            "deaths" => $this->deaths,
            "rank" => $this->rank
        ];
    }

    public function deserialize(array $data): void {
        $this->kills = $data["kills"] ?? 0;
        $this->deaths = $data["deaths"] ?? 0;
        $this->rank = $data["rank"] ?? "Guest";
    }
}
