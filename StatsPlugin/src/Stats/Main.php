<?php

declare(strict_types=1);

namespace Stats;

use ProfileSystem\Main as ProfileSystemMain;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\player\Player;

class Main extends PluginBase implements Listener {

    protected function onEnable(): void {
        // Register the stats component in ProfileSystem
        ProfileSystemMain::getInstance()->getProfileManager()->registerComponent("stats", StatsComponent::class);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDeath(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        $profile = ProfileSystemMain::getInstance()->getProfileManager()->getProfile($player);

        if ($profile !== null) {
            /** @var StatsComponent $stats */
            $stats = $profile->getComponent("stats");
            $stats->addDeath();
            $player->sendMessage("You have " . $stats->getDeaths() . " deaths!");
        }

        $cause = $player->getLastDamageCause();
        if ($cause instanceof EntityDamageByEntityEvent) {
            $killer = $cause->getDamager();
            if ($killer instanceof Player) {
                $killerProfile = ProfileSystemMain::getInstance()->getProfileManager()->getProfile($killer);
                if ($killerProfile !== null) {
                    /** @var StatsComponent $stats */
                    $stats = $killerProfile->getComponent("stats");
                    $stats->addKill();
                    $killer->sendMessage("You have " . $stats->getKills() . " kills!");
                }
            }
        }
    }
}
