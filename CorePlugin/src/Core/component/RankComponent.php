<?php

declare(strict_types=1);

namespace Core\component;

use ProfileSystem\component\ProfileComponent;

class RankComponent implements ProfileComponent {

    public function __construct(
        private string $rank = "Player",
        private string $prefix = "§7[Player] "
    ) {}

    public function getName(): string {
        return "rank";
    }

    public function getStorageTarget(): string {
        return "remote"; // MySQL for network-wide sync
    }

    public function getRank(): string {
        return $this->rank;
    }

    public function setRank(string $rank, string $prefix): void {
        $this->rank = $rank;
        $this->prefix = $prefix;
    }

    public function getPrefix(): string {
        return $this->prefix;
    }

    public function serialize(): array {
        return [
            "rank" => $this->rank,
            "prefix" => $this->prefix
        ];
    }

    public function deserialize(array $data): void {
        $this->rank = $data["rank"] ?? "Player";
        $this->prefix = $data["prefix"] ?? "§7[Player] ";
    }
}
