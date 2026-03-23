<?php

declare(strict_types=1);

namespace Core\component;

use ProfileSystem\component\ProfileComponent;

class SettingsComponent implements ProfileComponent {

    public function __construct(
        private bool $isChatEnabled = true,
        private bool $isFlightEnabled = false
    ) {}

    public function getName(): string {
        return "settings";
    }

    public function getStorageTarget(): string {
        return "local"; // SQLite for server-specific settings
    }

    public function isChatEnabled(): bool {
        return $this->isChatEnabled;
    }

    public function setChatEnabled(bool $enabled): void {
        $this->isChatEnabled = $enabled;
    }

    public function isFlightEnabled(): bool {
        return $this->isFlightEnabled;
    }

    public function setFlightEnabled(bool $enabled): void {
        $this->isFlightEnabled = $enabled;
    }

    public function serialize(): array {
        return [
            "chat" => $this->isChatEnabled,
            "flight" => $this->isFlightEnabled
        ];
    }

    public function deserialize(array $data): void {
        $this->isChatEnabled = (bool) ($data["chat"] ?? true);
        $this->isFlightEnabled = (bool) ($data["flight"] ?? false);
    }
}
