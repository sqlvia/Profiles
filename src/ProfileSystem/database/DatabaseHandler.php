<?php

declare(strict_types=1);

namespace ProfileSystem\database;

use ProfileSystem\Profile;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use pocketmine\plugin\Plugin;

class DatabaseHandler {

    private DataConnector $database;

    public function __construct(Plugin $plugin) {
        $this->database = libasynql::create($plugin, $plugin->getConfig()->get("database"), [
            "sqlite" => "queries.sql",
            "mysql" => "queries.sql"
        ]);
        $this->database->executeGeneric("profiles.init");
    }

    public function loadProfile(string $uuid, callable $callback): void {
        $this->database->executeSelect("profiles.load", ["uuid" => $uuid], function(array $rows) use ($callback) {
            $callback($rows[0] ?? null);
        });
    }

    public function saveProfile(Profile $profile): void {
        $this->database->executeInsert("profiles.upsert", [
            "uuid" => $profile->getUuid(),
            "username" => $profile->getUsername(),
            "components" => $profile->serializeComponents()
        ]);
    }

    public function close(): void {
        if (isset($this->database)) {
            $this->database->close();
        }
    }
}
