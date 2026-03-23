<?php

declare(strict_types=1);

namespace ProfileSystem\database;

use ProfileSystem\Profile;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use pocketmine\plugin\Plugin;

class DatabaseHandler {

    /** @var array<string, DataConnector> */
    private array $connectors = [];

    public function __construct(Plugin $plugin) {
        $config = $plugin->getConfig()->get("databases");

        foreach ($config as $name => $dbSettings) {
            $connector = libasynql::create($plugin, $dbSettings, [
                "sqlite" => "queries.sql",
                "mysql" => "queries.sql"
            ]);
            $this->connectors[$name] = $connector;
            $connector->executeGeneric("profiles.init");
        }
    }

    public function loadFromConnector(string $connectorName, string $uuid, callable $callback): void {
        if (!isset($this->connectors[$connectorName])) {
            $callback(null);
            return;
        }

        $this->connectors[$connectorName]->executeSelect("profiles.load", ["uuid" => $uuid], function(array $rows) use ($callback) {
            $callback($rows[0] ?? null);
        });
    }

    public function saveToConnector(string $connectorName, string $uuid, string $username, string $componentsJson): void {
        if (isset($this->connectors[$connectorName])) {
            $this->connectors[$connectorName]->executeInsert("profiles.upsert", [
                "uuid" => $uuid,
                "username" => $username,
                "components" => $componentsJson
            ]);
        }
    }

    public function close(): void {
        foreach ($this->connectors as $connector) {
            $connector->close();
        }
    }
}
