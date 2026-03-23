<?php

declare(strict_types=1);

namespace Core\command;

use Core\component\RankComponent;
use Core\component\EconomyComponent;
use Core\component\SettingsComponent;
use ProfileSystem\Main as ProfileSystemMain;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class MyDataCommand extends Command {

    public function __construct() {
        parent::__construct("mydata", "Check your current profile data", "/mydata");
        $this->setPermission("core.command.mydata");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game!");
            return;
        }

        $profile = ProfileSystemMain::getInstance()->getProfileManager()->getProfile($sender);
        if ($profile === null) {
            $sender->sendMessage("§cYour profile is still loading...");
            return;
        }

        /** @var RankComponent $rankComp */
        $rankComp = $profile->getComponent("rank");
        /** @var EconomyComponent $ecoComp */
        $ecoComp = $profile->getComponent("economy");
        /** @var SettingsComponent $settingsComp */
        $settingsComp = $profile->getComponent("settings");

        $sender->sendMessage("§l§b--- YOUR PROFILE ---");
        $sender->sendMessage("§eRank: §f" . $rankComp->getRank());
        $sender->sendMessage("§ePrefix: §f" . $rankComp->getPrefix());
        $sender->sendMessage("§eBalance: §a$" . number_format($ecoComp->getBalance(), 2));
        $sender->sendMessage("§eChat Enabled: " . ($settingsComp->isChatEnabled() ? "§aYes" : "§cNo"));
        $sender->sendMessage("§eFlight Enabled: " . ($settingsComp->isFlightEnabled() ? "§aYes" : "§cNo"));
    }
}
