<?php

declare(strict_types=1);

namespace ProfileSystem;

use ProfileSystem\component\ProfileComponent;

class Profile {

    /** @var array<string, ProfileComponent> */
    private array $components = [];

    public function __construct(
        private string $uuid,
        private string $username
    ) {}

    public function getUuid(): string {
        return $this->uuid;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function addComponent(ProfileComponent $component): void {
        $this->components[$component->getName()] = $component;
    }

    public function getComponent(string $name): ?ProfileComponent {
        return $this->components[$name] ?? null;
    }

    /**
     * @return array<string, ProfileComponent>
     */
    public function getComponents(): array {
        return $this->components;
    }

    /**
     * Serializes components for a specific storage target.
     */
    public function serializeForTarget(string $target): string {
        $data = [];
        foreach ($this->components as $name => $component) {
            if ($component->getStorageTarget() === $target) {
                $data[$name] = $component->serialize();
            }
        }
        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    /**
     * Deserializes components from JSON.
     */
    public function deserializeComponents(string $json): void {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        foreach ($data as $name => $componentData) {
            if (isset($this->components[$name])) {
                $this->components[$name]->deserialize($componentData);
            }
        }
    }
}
