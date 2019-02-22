<?php

namespace aircms\settings\Classes;

use aircms\settings\Contracts\Settings;
use aircms\settings\Models\Setting;

Class SettingsManager implements Settings
{
    /** @var Setting */
    private $setting;

    public function __construct()
    {
        $this->setting = new Setting();
    }

    public function get($keyTree, $default = null)
    {
        return $this->setting->get($keyTree, $default);
    }

    public function set($keyTree, $value)
    {
        return $this->setting->set($keyTree, $value);
    }

    public function all(): array
    {
        return $this->setting->cacheData(true);
    }
}
