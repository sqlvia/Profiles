<?php

declare(strict_types=1);

namespace ProfileSystem\component;

interface ProfileComponent {

    /**
     * Returns the unique name of the component.
     */
    public function getName(): string;

    /**
     * Returns where this component should be stored (e.g. "remote" or "local").
     */
    public function getStorageTarget(): string;

    /**
     * Serializes component data to an associative array for storage.
     * @return array<string, mixed>
     */
    public function serialize(): array;

    /**
     * Deserializes component data from an associative array.
     * @param array<string, mixed> $data
     */
    public function deserialize(array $data): void;
}
