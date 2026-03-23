-- #! sqlite
-- #{ profiles
-- # { init
CREATE TABLE IF NOT EXISTS player_profiles (
    uuid VARCHAR(36) PRIMARY KEY,
    username VARCHAR(16) NOT NULL,
    components TEXT NOT NULL
);
-- # }
-- # { load
-- # :uuid string
SELECT * FROM player_profiles WHERE uuid = :uuid;
-- # }
-- # { upsert
-- # :uuid string
-- # :username string
-- # :components string
INSERT INTO player_profiles (uuid, username, components)
VALUES (:uuid, :username, :components)
ON CONFLICT(uuid) DO UPDATE SET
    username = :username,
    components = :components;
-- # }
-- #}
