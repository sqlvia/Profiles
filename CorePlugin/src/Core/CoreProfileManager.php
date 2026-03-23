<?php

declare(strict_types=1);

namespace Core;

use pocketmine\player\Player;

class CoreProfileManager {

    /** @var \SplObjectStorage<Player, Profile> */
    private \SplObjectStorage $profiles;

    public function __construct() {
        $this->profiles = new \SplObjectStorage();
    }

    public function addProfile(Player $player, Profile $profile): void {
        $this->profiles->offsetSet($player, $profile);
    }

    public function getProfile(Player $player): ?Profile {
        return $this->profiles->offsetExists($player) ? $this->profiles->offsetGet($player) : null;
    }

    public function removeProfile(Player $player): void {
        $this->profiles->offsetUnset($player);
    }
}
