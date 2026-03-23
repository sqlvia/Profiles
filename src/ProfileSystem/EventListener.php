<?php

declare(strict_types=1);

namespace ProfileSystem;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener {

    public function __construct(private Main $plugin) {}

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $this->plugin->getProfileManager()->loadProfile($player, function(Profile $profile) use ($player) {
            $player->sendMessage("§aYour profile has been loaded!");
        });
    }

    public function onQuit(PlayerQuitEvent $event): void {
        $this->plugin->getProfileManager()->unloadProfile($event->getPlayer());
    }
}
