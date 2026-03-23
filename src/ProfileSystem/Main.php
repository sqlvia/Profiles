<?php

declare(strict_types=1);

namespace ProfileSystem;

use ProfileSystem\database\DatabaseHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

    private static self $instance;
    private DatabaseHandler $databaseHandler;
    private ProfileManager $profileManager;

    public static function getInstance(): self {
        return self::$instance;
    }

    protected function onEnable(): void {
        self::$instance = $this;
        $this->saveDefaultConfig();

        $this->databaseHandler = new DatabaseHandler($this);
        $this->profileManager = new ProfileManager($this->databaseHandler);

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
