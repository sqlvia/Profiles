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

    /**
     * Register a component class for all profiles.
     * @param string $componentClass Must implement ProfileComponent
     */
    public function registerComponent(string $name, string $componentClass): void {
        $this->componentRegistry[$name] = $componentClass;
    }

    public function loadProfile(Player $player, callable $onComplete): void {
        $uuid = $player->getUniqueId()->toString();
        $username = $player->getName();

        $this->db->loadProfile($uuid, function(?array $data) use ($player, $uuid, $username, $onComplete) {
            if (!$player->isOnline()) {
                return; // Prevent race condition/memory leak if player left during loading
            }

            $profile = new Profile($uuid, $username);

            // Instantiate registered components
            foreach ($this->componentRegistry as $class) {
                /** @var ProfileComponent $component */
                $component = new $class();
                $profile->addComponent($component);
            }

            if ($data !== null) {
                $profile->deserializeComponents($data["components"]);
            }

            $this->profiles[$uuid] = $profile;
            $onComplete($profile);
        });
    }

    public function getProfile(Player $player): ?Profile {
        return $this->profiles[$player->getUniqueId()->toString()] ?? null;
    }

    public function unloadProfile(Player $player): void {
        $uuid = $player->getUniqueId()->toString();
        if (isset($this->profiles[$uuid])) {
            $this->db->saveProfile($this->profiles[$uuid]);
            unset($this->profiles[$uuid]);
        }
    }

    public function saveAll(): void {
        foreach ($this->profiles as $profile) {
            $this->db->saveProfile($profile);
        }
    }
}
