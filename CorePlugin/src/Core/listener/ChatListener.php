<?php

declare(strict_types=1);

namespace Core\listener;

use Core\component\RankComponent;
use Core\component\SettingsComponent;
use ProfileSystem\Main as ProfileSystemMain;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class ChatListener implements Listener {

    public function onChat(PlayerChatEvent $event): void {
        $player = $event->getPlayer();
        $profile = ProfileSystemMain::getInstance()->getProfileManager()->getProfile($player);

        if ($profile === null) {
            $event->cancel();
            $player->sendMessage("§cYou cannot chat while your profile is loading!");
            return;
        }

        /** @var SettingsComponent $settings */
        $settings = $profile->getComponent("settings");
        if (!$settings->isChatEnabled()) {
            $event->cancel();
            $player->sendMessage("§cYou have disabled your own chat!");
            return;
        }

        /** @var RankComponent $rank */
        $rank = $profile->getComponent("rank");
        $event->setFormat($rank->getPrefix() . $player->getName() . ": §f" . $event->getMessage());
    }
}
