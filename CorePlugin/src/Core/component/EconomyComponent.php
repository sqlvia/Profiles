<?php

declare(strict_types=1);

namespace Core\component;

use ProfileSystem\component\ProfileComponent;

class EconomyComponent implements ProfileComponent {

    public function __construct(
        private float $balance = 1000.0
    ) {}

    public function getName(): string {
        return "economy";
    }

    public function getStorageTarget(): string {
        return "remote"; // MySQL for network-wide balance
    }

    public function getBalance(): float {
        return $this->balance;
    }

    public function setBalance(float $balance): void {
        $this->balance = $balance;
    }

    public function addMoney(float $amount): void {
        $this->balance += $amount;
    }

    public function reduceMoney(float $amount): void {
        $this->balance -= $amount;
    }

    public function serialize(): array {
        return [
            "balance" => $this->balance
        ];
    }

    public function deserialize(array $data): void {
        $this->balance = (float) ($data["balance"] ?? 1000.0);
    }
}
