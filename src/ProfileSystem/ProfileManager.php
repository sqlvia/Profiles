<?php

declare(strict_types=1);

namespace ProfileSystem;

use ProfileSystem\database\DatabaseHandler;
use ProfileSystem\component\ProfileComponent;
use pocketmine\player\Player;

class ProfileManager {

    /** @var array<string, Profile> */
    private array $profiles = [];

    /** @var array<string, string> */
    private array $componentRegistry = [];

    public function __construct(private DatabaseHandler $db) {}

    public function registerComponent(string $name, string $componentClass): void {
        $this->componentRegistry[$name] = $componentClass;
    }

    public function loadProfile(Player $player, callable $onComplete): void {
        $uuid = $player->getUniqueId()->toString();
        $username = $player->getName();

        $profile = new Profile($uuid, $username);
        foreach ($this->componentRegistry as $class) {
            /** @var ProfileComponent $component */
            $component = new $class();
            $profile->addComponent($component);
        }

        $targets = ["local", "remote"];
        $loaded = 0;
        $total = count($targets);

        foreach ($targets as $target) {
            $this->db->loadFromConnector($target, $uuid, function(?array $data) use ($player, $profile, $target, &$loaded, $total, $onComplete) {
                if ($data !== null) {
                    $profile->deserializeComponents($data["components"]);
                }

                $loaded++;
                if ($loaded === $total) {
                    if ($player->isOnline()) {
                        $this->profiles[$profile->getUuid()] = $profile;
                        $onComplete($profile);
                    }
                }
            });
        }
    }

    public function getProfile(Player $player): ?Profile {
        return $this->profiles[$player->getUniqueId()->toString()] ?? null;
    }

    public function unloadProfile(Player $player): void {
        $uuid = $player->getUniqueId()->toString();
        if (isset($this->profiles[$uuid])) {
            $this->saveProfile($this->profiles[$uuid]);
            unset($this->profiles[$uuid]);
        }
    }

    private function saveProfile(Profile $profile): void {
        foreach (["local", "remote"] as $target) {
            $json = $profile->serializeForTarget($target);
            // Only save if there's actually data or it exists
            $this->db->saveToConnector($target, $profile->getUuid(), $profile->getUsername(), $json);
        }
    }

    public function saveAll(): void {
        foreach ($this->profiles as $profile) {
            $this->saveProfile($profile);
        }
    }
}
