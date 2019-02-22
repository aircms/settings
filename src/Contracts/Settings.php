<?php

namespace aircms\settings\Contracts;

Interface Settings
{
    public function get($keyTree, $default = null);

    public function set($keyTree, $value);

    public function all(): array;
}
