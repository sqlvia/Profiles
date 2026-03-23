<?php

declare(strict_types=1);

namespace ProfileSystem;

use ProfileSystem\database\DatabaseHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

    private DatabaseHandler $databaseHandler;
    private ProfileManager $profileManager;

    protected function onEnable(): void {
        $this->saveDefaultConfig();

        $this->databaseHandler = new DatabaseHandler($this);
        $this->profileManager = new ProfileManager($this->databaseHandler);

        // Register default components
        $this->profileManager->registerComponent("stats", \ProfileSystem\component\StatsComponent::class);

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

    protected function onDisable(): void {
        if (isset($this->profileManager)) {
            $this->profileManager->saveAll();
        }
        if (isset($this->databaseHandler)) {
            $this->databaseHandler->close();
        }
    }

    public function getProfileManager(): ProfileManager {
        return $this->profileManager;
    }

    public function getDatabaseHandler(): DatabaseHandler {
        return $this->databaseHandler;
    }
}
