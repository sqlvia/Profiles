<?php

declare(strict_types=1);

namespace Core\listener;

use Core\Main;
use Core\Profile;
use ProfileSystem\Main as ProfileSystemMain;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class ProfileListener implements Listener {

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();

        // The ProfileSystem handles loading automatically.
        // We wait for it to finish and then wrap it.
        ProfileSystemMain::getInstance()->getProfileManager()->loadProfile($player, function($baseProfile) use ($player) {
            $coreProfile = new Profile($baseProfile);
            Main::getInstance()->getCoreProfileManager()->addProfile($player, $coreProfile);

            $player->sendMessage("§aCore profile initialized!");
        });
    }

    public function onQuit(PlayerQuitEvent $event): void {
        Main::getInstance()->getCoreProfileManager()->removeProfile($event->getPlayer());
    }
}
