<?php

declare(strict_types=1);

namespace Core;

use Core\component\RankComponent;
use Core\component\EconomyComponent;
use Core\component\SettingsComponent;
use ProfileSystem\Profile as BaseProfile;

class Profile {

    public function __construct(
        private BaseProfile $baseProfile
    ) {}

    public function getBaseProfile(): BaseProfile {
        return $this->baseProfile;
    }

    public function getRankComponent(): RankComponent {
        /** @var RankComponent $component */
        $component = $this->baseProfile->getComponent("rank");
        return $component;
    }

    public function getEconomyComponent(): EconomyComponent {
        /** @var EconomyComponent $component */
        $component = $this->baseProfile->getComponent("economy");
        return $component;
    }

    public function getSettingsComponent(): SettingsComponent {
        /** @var SettingsComponent $component */
        $component = $this->baseProfile->getComponent("settings");
        return $component;
    }
}
