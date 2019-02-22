<?php

namespace aircms\settings\Facades;

use aircms\settings\Models\Setting;
use Illuminate\Support\Facades\Facade;

/**
 * Class Settings
 *
 * @method static Setting get($keyTree,$default=null);
 * @method static Setting set($keyTree,$value);
 * @method static array all();
 *
 * @package aircms\settings\Facades
 */
class Settings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \aircms\settings\Contracts\Settings::class;
    }
}
