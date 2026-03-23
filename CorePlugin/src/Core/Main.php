<?php

declare(strict_types=1);

namespace Core;

use Core\component\RankComponent;
use Core\component\EconomyComponent;
use Core\component\SettingsComponent;
use Core\command\MyDataCommand;
use Core\listener\ChatListener;
use ProfileSystem\Main as ProfileSystemMain;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    private static self $instance;

    public static function getInstance(): self {
        return self::$instance;
    }

    protected function onEnable(): void {
        self::$instance = $this;

        // Register core components in the ProfileSystem
        $pm = ProfileSystemMain::getInstance()->getProfileManager();
        $pm->registerComponent("rank", RankComponent::class);
        $pm->registerComponent("economy", EconomyComponent::class);
        $pm->registerComponent("settings", SettingsComponent::class);

        // Register commands and events
        $this->getServer()->getCommandMap()->register("core", new MyDataCommand());
        $this->getServer()->getPluginManager()->registerEvents(new ChatListener(), $this);
    }
}
